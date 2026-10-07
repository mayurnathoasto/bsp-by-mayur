<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserBusinessProfile;
use App\Services\User\SellerDashboardViewData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MobileSellerController extends Controller
{
    public function dashboard(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        $user = $this->apiUser($request);
        $period = (string) $request->input('period', 'month');
        $month = $request->filled('month') ? (int) $request->input('month') : null;
        $year = $request->filled('year') ? (int) $request->input('year') : null;

        $data = $dashboardViewData->build($user, $period, $month, $year);

        return response()->json([
            'user' => $this->transformCurrentUser($data['user']),
            'stats' => $data['stats'],
            'chart' => $data['chart'],
            'profile_completeness' => $data['user']->profile_completeness,
            'display_location' => implode(', ', array_values(array_filter([
                $data['displayCity'],
                $data['displayState'],
                $data['displayCountry'],
            ]))),
            'flags' => [
                'has_business_profile' => $data['user']->businessProfile !== null,
                'has_bank_details' => $data['user']->bankDetail !== null,
            ],
            'recent_products' => $data['recentProducts']
                ->loadMissing(['category', 'subcategory'])
                ->map(fn(Product $product) => $this->transformSellerProduct($product))
                ->values(),
            'recent_inquiries' => $data['recentInquiries']
                ->loadMissing(['product', 'buyer'])
                ->map(fn(ProductInquiry $inquiry) => $this->transformInquiry($inquiry))
                ->values(),
        ]);
    }

    public function personalProfile(Request $request)
    {
        $user = $this->apiUser($request)->loadMissing(['businessProfile', 'city']);
        [$firstName, $lastName] = $this->splitName($user->name);
        $country = $this->currentCountryForUser($user);
        $profileConfig = $this->profileLocalizationCountryConfig($country);

        return response()->json([
            'user' => $this->transformCurrentUser($user),
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $user->name,
                'email' => $user->email,
                'alternate_email' => $user->alternate_email,
                'phone' => $user->phone,
                'office_contact' => $user->office_contact,
                'alternate_mobile' => $user->alternate_mobile,
                'whatsapp_number' => $user->whatsapp_number,
                'designation' => $user->designation,
                'profile_photo_path' => $user->profile_photo_path,
                'company_name' => $user->businessProfile?->company_name ?? $user->company_name,
                'country_code' => $country,
                'city_name' => $user->businessProfile?->city_name ?? $user->city?->name,
                'state_name' => $user->businessProfile?->state_name,
            ],
            'meta' => [
                'dial_code' => $profileConfig['dial_code'] ?? '+00',
            ],
        ]);
    }

    public function updatePersonal(Request $request)
    {
        $user = $this->apiUser($request);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],
            'email' => [
                'nullable',
                'email',
                'max:180',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'alternate_email' => ['nullable', 'email', 'max:180'],
            'office_contact' => ['nullable', 'string', 'max:30'],
            'alternate_mobile' => ['nullable', 'string', 'max:30'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'designation' => ['nullable', 'string', 'max:120'],
            'profile_photo_path' => ['nullable', 'string', 'max:2048'],
        ]);

        $user->name = trim($data['first_name'] . ' ' . $data['last_name']);

        if (array_key_exists('email', $data)) {
            $user->email = $data['email'];
        }

        $user->alternate_email = $data['alternate_email'] ?? null;
        $user->office_contact = $data['office_contact'] ?? null;
        $user->alternate_mobile = $data['alternate_mobile'] ?? null;
        $user->whatsapp_number = $data['whatsapp_number'] ?? null;
        $user->designation = $data['designation'] ?? null;
        $user->profile_photo_path = $data['profile_photo_path'] ?? $user->profile_photo_path;
        $user->save();

        return response()->json([
            'message' => 'Personal details saved.',
            'user' => $this->transformCurrentUser($user->fresh(['businessProfile', 'city'])),
        ]);
    }

    public function businessProfile(Request $request)
    {
        $user = $this->apiUser($request)->loadMissing(['businessProfile', 'city']);
        $country = $this->currentCountryForUser($user);

        return response()->json([
            'user' => $this->transformCurrentUser($user),
            'profile' => $this->businessProfilePayload($user),
            'form' => [
                'country_code' => $country,
                'available_countries' => collect(config('countries.supported', []))
                    ->filter(fn(array $countryConfig) => !empty($countryConfig['live']))
                    ->map(fn(array $countryConfig, string $code) => [
                        'code' => $code,
                        'name' => $countryConfig['name'],
                        'currency' => $countryConfig['currency'],
                    ])
                    ->values(),
                'country_config' => $this->businessProfileCountryConfig($country),
                'profile_localization' => $this->profileLocalizationCountryConfig($country),
            ],
        ]);
    }

    public function updateBusinessProfile(Request $request)
    {
        $user = $this->apiUser($request);
        $resolvedCountry = $this->normalizeBusinessProfileCountry(
            $request->input('country_code'),
            $this->currentCountryForUser($user)
        );

        if ($request->filled('form_type')) {
            $formType = (string) $request->input('form_type');
            $businessProfile = UserBusinessProfile::firstOrCreate(['user_id' => $user->id]);
            $existingInfo = $businessProfile->additional_info ?? [];
            $inputInfo = $request->input('additional_info', []);
            $inputInfo = is_array($inputInfo) ? $inputInfo : [];

            if ($formType === 'additional') {
                $request->validate([
                    'additional_info.video_url' => ['nullable', 'url', 'max:2048'],
                ]);
            }

            $expectedKeys = match ($formType) {
                'additional' => [
                    'logo',
                    'photos',
                    'video_url',
                    'video_title',
                    'video_category',
                    'company_description',
                    'payment_modes',
                    'working_days_from',
                    'working_days_to',
                    'working_time_from',
                    'working_time_to',
                    'preferred_states',
                    'excluded_cities',
                    'preferred_cities',
                ],
                'certification' => [
                    'certifications',
                    'awards',
                    'standard_certification',
                    'membership_affiliations',
                    'award_recognition',
                ],
                default => [],
            };

            $mergedInfo = $existingInfo;

            foreach ($expectedKeys as $key) {
                $value = $inputInfo[$key] ?? (in_array($key, ['photos', 'payment_modes', 'certifications', 'awards'], true) ? [] : null);
                $mergedInfo[$key] = $value;

                if ($key === 'photos') {
                    $businessProfile->company_photos = $value;
                    continue;
                }

                if (in_array($key, [
                    'logo',
                    'video_url',
                    'video_title',
                    'video_category',
                    'payment_modes',
                    'excluded_cities',
                    'working_days_from',
                    'working_days_to',
                    'working_time_from',
                    'working_time_to',
                    'preferred_cities',
                    'preferred_states',
                    'company_description',
                    'standard_certification',
                    'membership_affiliations',
                    'award_recognition',
                    'certifications',
                    'awards',
                ], true)) {
                    $businessProfile->{$key} = $value;
                }
            }

            $businessProfile->additional_info = $mergedInfo;
            $businessProfile->save();

            return response()->json([
                'message' => 'Information updated successfully.',
                'profile' => $this->businessProfilePayload($user->fresh(['businessProfile', 'city'])),
            ]);
        }

        $supportedCountries = config('countries.supported', []);
        $resolvedCurrency = $supportedCountries[$resolvedCountry]['currency'] ?? config('countries.default_currency', 'INR');

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:180'],
            'company_logo_path' => ['nullable', 'string', 'max:2048'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'fax_number' => ['nullable', 'string', 'max:30'],
            'ownership_type' => ['nullable', 'string', 'max:40'],
            'annual_turnover_currency' => ['nullable', 'string', 'max:10'],
            'annual_turnover_amount' => ['nullable', 'numeric', 'min:0'],
            'annual_turnover_unit' => ['nullable', 'string', 'max:20'],
            'year_established' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'employee_count' => ['nullable', 'integer', 'min:0', 'max:10000000'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'country_code' => ['nullable', 'string', 'max:5'],
            'place_id' => ['nullable', 'string', 'max:120'],
            'formatted_address' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'city_name' => ['required', 'string', 'max:120'],
            'state_name' => ['nullable', 'string', 'max:120'],
            'tax_id_type' => ['nullable', 'string', 'max:20'],
            'tax_id_number' => ['nullable', 'string', 'max:50'],
            'tax_aadhaar' => ['nullable', 'string', 'max:20'],
            'tax_pan' => ['nullable', 'string', 'max:20'],
            'tax_iec' => ['nullable', 'string', 'max:30'],
            'tax_tan' => ['nullable', 'string', 'max:30'],
            'tax_vat' => ['nullable', 'string', 'max:30'],
            'doc_gst' => ['nullable', 'string', 'max:2048'],
            'doc_aadhaar' => ['nullable', 'string', 'max:2048'],
            'doc_pan' => ['nullable', 'string', 'max:2048'],
            'doc_iec' => ['nullable', 'string', 'max:2048'],
        ]);

        $matchedCityId = $data['city_id'] ?? null;
        if (!$matchedCityId && !empty($data['city_name'])) {
            $matchedCity = City::where('name', $data['city_name'])
                ->where('is_active', true)
                ->first();
            if ($matchedCity) {
                $matchedCityId = $matchedCity->id;
            }
        }

        $user->company_name = $data['company_name'];
        $user->company_phone = $data['company_phone'] ?? null;
        $user->company_address = $data['company_address'] ?? null;
        $user->tax_id_type = $data['tax_id_type'] ?? null;
        $user->tax_id_number = $data['tax_id_number'] ?? null;
        $user->city_id = $matchedCityId ?? $user->city_id;
        $user->pincode = $data['pincode'] ?? null;
        $user->country_code = $data['country_code'] ?? $resolvedCountry;

        if (in_array($user->seller_status, [User::SELLER_STATUS_NONE, User::SELLER_STATUS_REJECTED], true)) {
            $user->seller_status = User::SELLER_STATUS_PENDING;
            $user->seller_submitted_at = now();
            $user->seller_verified_at = null;
            $user->seller_rejection_reason = null;
        }

        $user->save();

        UserBusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $data['company_name'],
                'company_logo_path' => $data['company_logo_path'] ?? null,
                'company_phone' => $data['company_phone'] ?? null,
                'fax_number' => $data['fax_number'] ?? null,
                'ownership_type' => $data['ownership_type'] ?? null,
                'annual_turnover_currency' => $data['annual_turnover_currency'] ?? $resolvedCurrency,
                'annual_turnover_amount' => $data['annual_turnover_amount'] ?? null,
                'annual_turnover_unit' => $data['annual_turnover_unit'] ?? null,
                'year_established' => $data['year_established'] ?? null,
                'employee_count' => $data['employee_count'] ?? null,
                'address_line' => $data['company_address'] ?? null,
                'city_id' => $matchedCityId ?? $data['city_id'] ?? null,
                'city_name' => $data['city_name'] ?? null,
                'state_name' => $data['state_name'] ?? null,
                'country_code' => $data['country_code'] ?? $resolvedCountry,
                'pincode' => $data['pincode'] ?? null,
                'place_id' => $data['place_id'] ?? null,
                'formatted_address' => $data['formatted_address'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,
                'tax_ids_json' => array_filter([
                    'tax_id_type' => $data['tax_id_type'] ?? null,
                    'tax_id_number' => $data['tax_id_number'] ?? null,
                    'aadhaar' => $data['tax_aadhaar'] ?? null,
                    'pan' => $data['tax_pan'] ?? null,
                    'iec' => $data['tax_iec'] ?? null,
                    'tan' => $data['tax_tan'] ?? null,
                    'vat' => $data['tax_vat'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''),
                'documents_json' => array_filter([
                    'gst' => $data['doc_gst'] ?? null,
                    'aadhaar' => $data['doc_aadhaar'] ?? null,
                    'pan' => $data['doc_pan'] ?? null,
                    'iec' => $data['doc_iec'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''),
            ]
        );

        return response()->json([
            'message' => 'Business profile updated successfully.',
            'user' => $this->transformCurrentUser($user->fresh(['businessProfile', 'city'])),
            'profile' => $this->businessProfilePayload($user->fresh(['businessProfile', 'city'])),
        ]);
    }

    public function bankDetails(Request $request)
    {
        $user = $this->apiUser($request)->loadMissing(['businessProfile', 'bankDetail']);
        $country = $this->currentCountryForUser($user);
        $profileConfig = $this->profileLocalizationCountryConfig($country);

        return response()->json([
            'user' => $this->transformCurrentUser($user),
            'bank_details' => $this->bankDetailsPayload($user),
            'form' => [
                'country_code' => $country,
                'bank' => $profileConfig['bank'] ?? [],
                'dial_code' => $profileConfig['dial_code'] ?? '+00',
            ],
        ]);
    }

    public function updateBankDetails(Request $request)
    {
        $user = $this->apiUser($request)->loadMissing(['businessProfile']);
        $country = $this->currentCountryForUser($user);

        $rules = [
            'account_type' => ['nullable', 'string', 'max:30'],
            'account_holder_name' => ['required', 'string', 'max:120'],
            'account_number' => ['required', 'string', 'max:40'],
            'confirm_account_number' => ['required', 'same:account_number'],
            'ifsc_code' => ['required', 'string', 'max:30'],
            'linked_mobile' => ['required', 'string', 'max:30'],
        ];

        if ($country === 'IN') {
            $rules['account_number'] = ['required', 'digits_between:9,18'];
            $rules['ifsc_code'] = ['required', 'string', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'];
            $rules['linked_mobile'] = ['required', 'digits:10'];
        }

        $data = $request->validate($rules, [
            'confirm_account_number.same' => 'Account numbers do not match.',
            'ifsc_code.regex' => 'Invalid IFSC code format.',
        ]);

        UserBankDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'account_type' => $data['account_type'] ?? null,
                'account_holder_name' => $data['account_holder_name'],
                'account_number' => $data['account_number'],
                'ifsc_code' => strtoupper((string) $data['ifsc_code']),
                'linked_mobile' => $data['linked_mobile'],
            ]
        );

        return response()->json([
            'message' => 'Bank details saved.',
            'bank_details' => $this->bankDetailsPayload($user->fresh(['businessProfile', 'bankDetail'])),
        ]);
    }

    public function inquiries(Request $request)
    {
        $user = $this->apiUser($request);
        $perPage = max(1, min(30, (int) $request->input('per_page', 15)));

        $query = ProductInquiry::query()
            ->with(['product', 'buyer'])
            ->where('seller_user_id', $user->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $inquiries = $query->paginate($perPage);

        return response()->json([
            'data' => collect($inquiries->items())
                ->map(fn(ProductInquiry $inquiry) => $this->transformInquiry($inquiry))
                ->values(),
            'meta' => [
                'current_page' => $inquiries->currentPage(),
                'last_page' => $inquiries->lastPage(),
                'per_page' => $inquiries->perPage(),
                'total' => $inquiries->total(),
            ],
            'status_options' => ['new', 'contacted', 'closed'],
        ]);
    }

    public function showInquiry(Request $request, ProductInquiry $inquiry)
    {
        $this->ensureSellerOwnsInquiry($request, $inquiry);
        $inquiry->loadMissing(['product.category', 'product.subcategory', 'buyer', 'seller']);

        return response()->json([
            'inquiry' => $this->transformInquiry($inquiry, true),
        ]);
    }

    public function updateInquiryStatus(Request $request, ProductInquiry $inquiry)
    {
        $this->ensureSellerOwnsInquiry($request, $inquiry);

        $data = $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $inquiry->update([
            'status' => $data['status'],
        ]);

        return response()->json([
            'message' => 'Inquiry status updated successfully.',
            'inquiry' => $this->transformInquiry($inquiry->fresh(['product', 'buyer']), true),
        ]);
    }

    public function productFormMeta()
    {
        $categories = Category::where('is_active', true)
            ->with([
                'subcategories' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'categories' => $categories->map(function (Category $category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'subcategories' => $category->subcategories->map(fn(Subcategory $subcategory) => [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                    ])->values(),
                ];
            })->values(),
            'shipping_options' => [
                'Air Freight',
                'Sea Freight',
                'Road Transport',
                'Rail Transport',
                'Courier / Parcel',
                'Local Delivery',
                'Pickup Available',
            ],
            'payment_options' => [
                'Bank Transfer (T/T)',
                'Letter of Credit (L/C)',
                'Wire Transfer / SWIFT',
                'UPI',
                'Credit Card',
                'Debit Card',
                'PayPal',
                'Western Union',
                'Cash in Advance',
            ],
            'support_options' => [
                'OEM',
                'ODM',
                'OEM / ODM',
                'Private Label',
                'Standard Supply Only',
            ],
            'lead_time_units' => [
                'Days',
                'Business Days',
                'Weeks',
            ],
        ]);
    }

    public function products(Request $request)
    {
        $user = $this->apiUser($request);
        $perPage = max(1, min(30, (int) $request->input('per_page', 15)));
        $status = trim((string) $request->input('status', ''));

        $query = Product::query()
            ->where('user_id', $user->id)
            ->with(['category', 'subcategory', 'specifications'])
            ->latest();

        if (in_array($status, [Product::STATUS_DRAFT, Product::STATUS_PENDING, Product::STATUS_LIVE], true)) {
            $query->where('status', $status);
        }

        $products = $query->paginate($perPage);

        return response()->json([
            'data' => collect($products->items())
                ->map(fn(Product $product) => $this->transformSellerProduct($product))
                ->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
            'status_options' => [
                Product::STATUS_DRAFT,
                Product::STATUS_PENDING,
                Product::STATUS_LIVE,
            ],
        ]);
    }

    public function showProduct(Request $request, Product $product)
    {
        $this->ensureSellerOwnsProduct($request, $product);
        $product->loadMissing(['category', 'subcategory', 'specifications']);

        return response()->json([
            'product' => $this->transformSellerProduct($product, true),
        ]);
    }

    public function storeProduct(Request $request)
    {
        $user = $this->apiUser($request)->loadMissing(['businessProfile']);
        $validated = $this->validateProductPayload($request);
        $resolved = $this->resolvedCountryAndCurrencyForUser($user);
        $specRows = $this->normalizedSpecificationsFromRequest($request);

        $product = DB::transaction(function () use ($validated, $user, $resolved, $specRows) {
            $product = Product::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'price_to' => $validated['price_to'] ?? null,
                'currency' => $resolved['currency'],
                'country_code' => $resolved['country'],
                'min_order_quantity' => $validated['min_order_quantity'],
                'images' => $validated['images'],
                'lead_time_value' => $validated['lead_time_value'] ?? null,
                'lead_time_unit' => $validated['lead_time_unit'] ?? null,
                'shipping_options' => array_values(array_filter($validated['shipping_options'] ?? [])),
                'payment_options' => array_values(array_filter($validated['payment_options'] ?? [])),
                'sample_moq' => $validated['sample_moq'] ?? null,
                'warranty' => $validated['warranty'] ?? null,
                'support_option' => $validated['support_option'] ?? null,
                'status' => Product::STATUS_PENDING,
            ]);

            $this->syncSpecifications($product, $specRows);

            return $product;
        });

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $this->transformSellerProduct($product->load(['category', 'subcategory', 'specifications']), true),
        ], 201);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->ensureSellerOwnsProduct($request, $product);
        $validated = $this->validateProductPayload($request);
        $specRows = $this->normalizedSpecificationsFromRequest($request);

        DB::transaction(function () use ($product, $validated, $specRows) {
            $product->update([
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'price_to' => $validated['price_to'] ?? null,
                'min_order_quantity' => $validated['min_order_quantity'],
                'images' => $validated['images'],
                'lead_time_value' => $validated['lead_time_value'] ?? null,
                'lead_time_unit' => $validated['lead_time_unit'] ?? null,
                'shipping_options' => array_values(array_filter($validated['shipping_options'] ?? [])),
                'payment_options' => array_values(array_filter($validated['payment_options'] ?? [])),
                'sample_moq' => $validated['sample_moq'] ?? null,
                'warranty' => $validated['warranty'] ?? null,
                'support_option' => $validated['support_option'] ?? null,
                'status' => Product::STATUS_PENDING,
            ]);

            $this->syncSpecifications($product, $specRows);
        });

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $this->transformSellerProduct($product->fresh(['category', 'subcategory', 'specifications']), true),
        ]);
    }

    public function destroyProduct(Request $request, Product $product)
    {
        $this->ensureSellerOwnsProduct($request, $product);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }

    protected function apiUser(Request $request): User
    {
        /** @var \App\Models\User $user */
        $user = $request->attributes->get('apiUser');

        return $user;
    }

    protected function transformCurrentUser(User $user): array
    {
        $businessProfile = $user->businessProfile;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => $this->currentCountryForUser($user),
            'city_name' => $businessProfile?->city_name ?? $user->city?->name,
            'state_name' => $businessProfile?->state_name,
            'seller_status' => $user->seller_status ?? User::SELLER_STATUS_NONE,
            'company_name' => $businessProfile?->company_name ?? $user->company_name,
            'designation' => $user->designation,
            'membership_label' => $user->seller_status === User::SELLER_STATUS_APPROVED ? 'Verified Seller' : 'Free Member',
            'profile_image_url' => $user->profile_photo_path,
        ];
    }

    protected function transformInquiry(ProductInquiry $inquiry, bool $detailed = false): array
    {
        $payload = [
            'id' => $inquiry->id,
            'status' => $inquiry->status,
            'message' => $inquiry->message,
            'name' => $inquiry->name,
            'email' => $inquiry->email,
            'phone' => $inquiry->phone,
            'created_at' => optional($inquiry->created_at)->toISOString(),
            'product' => $inquiry->product ? [
                'id' => $inquiry->product->id,
                'name' => $inquiry->product->name,
                'image_url' => collect($inquiry->product->images)->first(),
            ] : null,
            'buyer' => $inquiry->buyer ? [
                'id' => $inquiry->buyer->id,
                'name' => $inquiry->buyer->name,
                'email' => $inquiry->buyer->email,
                'phone' => $inquiry->buyer->phone,
            ] : null,
        ];

        if ($detailed) {
            $payload['seller_user_id'] = $inquiry->seller_user_id;
            $payload['buyer_user_id'] = $inquiry->buyer_user_id;
        }

        return $payload;
    }

    protected function transformSellerProduct(Product $product, bool $detailed = false): array
    {
        $payload = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'status' => $product->status,
            'price' => $product->price !== null ? (float) $product->price : null,
            'price_to' => $product->price_to !== null ? (float) $product->price_to : null,
            'currency' => $product->currency,
            'country_code' => $product->country_code,
            'min_order_quantity' => (int) ($product->min_order_quantity ?? 1),
            'lead_time_value' => $product->lead_time_value,
            'lead_time_unit' => $product->lead_time_unit,
            'lead_time_display' => $product->lead_time_display,
            'shipping_options' => array_values(array_filter($product->shipping_options ?? [])),
            'payment_options' => array_values(array_filter($product->payment_options ?? [])),
            'sample_moq' => $product->sample_moq,
            'warranty' => $product->warranty,
            'support_option' => $product->support_option,
            'images' => array_values(array_filter($product->images ?? [])),
            'image_url' => collect($product->images ?? [])->first(),
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'subcategory' => $product->subcategory ? [
                'id' => $product->subcategory->id,
                'name' => $product->subcategory->name,
            ] : null,
            'created_at' => optional($product->created_at)->toISOString(),
            'updated_at' => optional($product->updated_at)->toISOString(),
        ];

        if ($detailed) {
            $payload['specifications'] = $product->specifications
                ->map(fn($specification) => [
                    'id' => $specification->id,
                    'key' => $specification->spec_key,
                    'value' => $specification->spec_value,
                ])
                ->values();
        }

        return $payload;
    }

    protected function businessProfilePayload(User $user): array
    {
        $profile = $user->businessProfile;
        $taxIds = $profile?->tax_ids_json ?? [];
        $documents = $profile?->documents_json ?? [];
        $country = $this->currentCountryForUser($user);

        return [
            'company_name' => $profile?->company_name ?? $user->company_name,
            'company_logo_path' => $profile?->company_logo_path,
            'company_phone' => $profile?->company_phone ?? $user->company_phone,
            'company_address' => $profile?->address_line ?? $user->company_address,
            'fax_number' => $profile?->fax_number,
            'ownership_type' => $profile?->ownership_type,
            'annual_turnover_currency' => $profile?->annual_turnover_currency ?? (config('countries.supported.' . $country . '.currency') ?? config('countries.default_currency', 'INR')),
            'annual_turnover_amount' => $profile?->annual_turnover_amount,
            'annual_turnover_unit' => $profile?->annual_turnover_unit,
            'year_established' => $profile?->year_established,
            'employee_count' => $profile?->employee_count,
            'city_id' => $profile?->city_id ?? $user->city_id,
            'city_name' => $profile?->city_name ?? $user->city?->name,
            'state_name' => $profile?->state_name,
            'country_code' => $country,
            'pincode' => $profile?->pincode ?? $user->pincode,
            'place_id' => $profile?->place_id,
            'formatted_address' => $profile?->formatted_address,
            'lat' => $profile?->lat,
            'lng' => $profile?->lng,
            'tax_id_type' => $taxIds['tax_id_type'] ?? $user->tax_id_type,
            'tax_id_number' => $taxIds['tax_id_number'] ?? $user->tax_id_number,
            'tax_aadhaar' => $taxIds['aadhaar'] ?? null,
            'tax_pan' => $taxIds['pan'] ?? null,
            'tax_iec' => $taxIds['iec'] ?? null,
            'tax_tan' => $taxIds['tan'] ?? null,
            'tax_vat' => $taxIds['vat'] ?? null,
            'doc_gst' => $documents['gst'] ?? null,
            'doc_aadhaar' => $documents['aadhaar'] ?? null,
            'doc_pan' => $documents['pan'] ?? null,
            'doc_iec' => $documents['iec'] ?? null,
            'additional_info' => [
                'logo' => $profile?->logo,
                'photos' => $profile?->company_photos ?? [],
                'video_url' => $profile?->video_url,
                'video_title' => $profile?->video_title,
                'video_category' => $profile?->video_category,
                'company_description' => $profile?->company_description,
                'payment_modes' => $profile?->payment_modes ?? [],
                'working_days_from' => $profile?->working_days_from,
                'working_days_to' => $profile?->working_days_to,
                'working_time_from' => $profile?->working_time_from,
                'working_time_to' => $profile?->working_time_to,
                'preferred_states' => $profile?->preferred_states,
                'excluded_cities' => $profile?->excluded_cities,
                'preferred_cities' => $profile?->preferred_cities,
            ],
            'certification_info' => [
                'certifications' => $profile?->certifications ?? [],
                'awards' => $profile?->awards ?? [],
                'standard_certification' => $profile?->standard_certification,
                'membership_affiliations' => $profile?->membership_affiliations,
                'award_recognition' => $profile?->award_recognition,
            ],
        ];
    }

    protected function bankDetailsPayload(User $user): array
    {
        $bank = $user->bankDetail;

        return [
            'account_type' => $bank?->account_type,
            'account_holder_name' => $bank?->account_holder_name,
            'account_number' => $bank?->account_number,
            'ifsc_code' => $bank?->ifsc_code,
            'linked_mobile' => $bank?->linked_mobile,
        ];
    }

    protected function resolvedCountryAndCurrencyForUser(User $user): array
    {
        $supportedCountries = config('countries.supported', []);
        $country = $this->currentCountryForUser($user);

        return [
            'country' => $country,
            'currency' => strtoupper($supportedCountries[$country]['currency'] ?? config('countries.default_currency', 'INR')),
        ];
    }

    protected function validateProductPayload(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'moq' => ['nullable', 'integer', 'min:1'],
            'min_order_quantity' => ['nullable', 'integer', 'min:1'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['nullable', 'url'],
            'specifications' => ['nullable', 'array'],
            'specifications.*.key' => ['nullable', 'string', 'max:255'],
            'specifications.*.value' => ['nullable', 'string', 'max:2000'],
            'spec_keys' => ['nullable', 'array'],
            'spec_keys.*' => ['nullable', 'string', 'max:255'],
            'spec_values' => ['nullable', 'array'],
            'spec_values.*' => ['nullable', 'string', 'max:2000'],
            'lead_time_value' => ['nullable', 'integer', 'min:1', 'max:365'],
            'lead_time_unit' => ['nullable', 'string', 'in:Days,Business Days,Weeks'],
            'shipping_options' => ['nullable', 'array'],
            'shipping_options.*' => ['nullable', 'string', 'max:100'],
            'payment_options' => ['nullable', 'array'],
            'payment_options.*' => ['nullable', 'string', 'max:100'],
            'sample_moq' => ['nullable', 'integer', 'min:1'],
            'warranty' => ['nullable', 'string', 'max:255'],
            'support_option' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['images'] = array_values(array_filter($validated['images'] ?? []));
        $validated['min_order_quantity'] = (int) ($validated['min_order_quantity'] ?? $validated['moq'] ?? 1);

        return $validated;
    }

    protected function normalizedSpecificationsFromRequest(Request $request): array
    {
        $specifications = $request->input('specifications', []);
        if (is_array($specifications) && !empty($specifications)) {
            $rows = [];

            foreach ($specifications as $item) {
                $key = trim((string) ($item['key'] ?? ''));
                $value = trim((string) ($item['value'] ?? ''));

                if ($key === '') {
                    continue;
                }

                $rows[] = [
                    'spec_key' => $key,
                    'spec_value' => $value !== '' ? $value : null,
                    'sort_order' => count($rows),
                ];
            }

            return $rows;
        }

        $keys = $request->input('spec_keys', []);
        $values = $request->input('spec_values', []);
        $keys = is_array($keys) ? array_values($keys) : [];
        $values = is_array($values) ? array_values($values) : [];

        $max = max(count($keys), count($values));
        $rows = [];

        for ($index = 0; $index < $max; $index++) {
            $key = trim((string) ($keys[$index] ?? ''));
            $value = trim((string) ($values[$index] ?? ''));

            if ($key === '') {
                continue;
            }

            $rows[] = [
                'spec_key' => $key,
                'spec_value' => $value !== '' ? $value : null,
                'sort_order' => count($rows),
            ];
        }

        return $rows;
    }

    protected function syncSpecifications(Product $product, array $rows): void
    {
        $product->specifications()->delete();

        if (empty($rows)) {
            return;
        }

        $product->specifications()->createMany($rows);
    }

    protected function ensureSellerOwnsInquiry(Request $request, ProductInquiry $inquiry): void
    {
        abort_unless((int) $inquiry->seller_user_id === (int) $this->apiUser($request)->id, 403);
    }

    protected function ensureSellerOwnsProduct(Request $request, Product $product): void
    {
        abort_unless((int) $product->user_id === (int) $this->apiUser($request)->id, 403);
    }

    protected function businessProfileCountryConfig(string $countryCode): array
    {
        $countryCode = strtoupper($countryCode);
        $all = config('business_profile.countries', []);

        if (isset($all[$countryCode])) {
            return $all[$countryCode];
        }

        return $all['DEFAULT_INTL'] ?? [
            'address' => [],
            'tax_fields' => [],
            'document_fields' => [],
        ];
    }

    protected function profileLocalizationCountryConfig(string $countryCode): array
    {
        $countryCode = strtoupper($countryCode);
        $all = config('profile_localization.countries', []);

        if (isset($all[$countryCode])) {
            return $all[$countryCode];
        }

        return $all['DEFAULT_INTL'] ?? [];
    }

    protected function normalizeBusinessProfileCountry(?string $requestedCountry, ?string $fallbackCountry): string
    {
        $country = strtoupper($requestedCountry ?: $fallbackCountry ?: config('business_profile.default_country', 'IN'));
        $supported = config('countries.supported', []);

        return isset($supported[$country]) ? $country : config('business_profile.default_country', 'IN');
    }

    protected function currentCountryForUser(User $user): string
    {
        $country = strtoupper($user->businessProfile?->country_code ?? $user->country_code ?? config('countries.default_country', 'IN'));
        $supported = config('countries.supported', []);

        return isset($supported[$country]) ? $country : strtoupper(config('countries.default_country', 'IN'));
    }

    protected function splitName(?string $name): array
    {
        $trimmed = trim((string) $name);

        if ($trimmed === '') {
            return ['', ''];
        }

        $parts = preg_split('/\s+/', $trimmed) ?: [];

        if (count($parts) === 1) {
            return [$parts[0], ''];
        }

        $firstName = array_shift($parts);

        return [$firstName, implode(' ', $parts)];
    }
}
