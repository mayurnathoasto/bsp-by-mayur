<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use App\Models\UserBankDetail;
use App\Models\UserBusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\User\SellerDashboardViewData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductInquiry;
use App\Models\Product;


class UserProfileController extends Controller
{
    public function show(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        return view('user.dashboard', $dashboardViewData->build(
            $request->user(),
            (string) $request->get('period', 'month'),
            $request->get('month'),
            $request->get('year')
        ));
    }

    public function businessProfile(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        return view('user.dashboard', $dashboardViewData->build(
            $request->user(),
            (string) $request->get('period', 'month'),
            $request->get('month'),
            $request->get('year')
        ));
    }

    public function bankDetails(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        return view('user.dashboard', $dashboardViewData->build(
            $request->user(),
            (string) $request->get('period', 'month'),
            $request->get('month'),
            $request->get('year')
        ));
    }

    public function manageUsers(Request $request, SellerDashboardViewData $dashboardViewData)
    {
        return view('user.dashboard', $dashboardViewData->build(
            $request->user(),
            (string) $request->get('period', 'month'),
            $request->get('month'),
            $request->get('year')
        ));
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

    protected function normalizeBusinessProfileCountry(?string $requestedCountry, ?string $sessionCountry): string
    {
        $country = strtoupper($requestedCountry ?: $sessionCountry ?: config('business_profile.default_country', 'IN'));
        $supported = config('countries.supported', []);

        return isset($supported[$country]) ? $country : config('business_profile.default_country', 'IN');
    }

    /**
     * NEW: Personal Details form from dashboard
     * Stores full name in users.name (first + last), plus extra fields on users table.
     */
    public function updatePersonal(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:60'],
            'last_name' => ['required', 'string', 'max:60'],

            // Optional: allow updating email for phone-based accounts too
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

        $fullName = trim($data['first_name'] . ' ' . $data['last_name']);
        $user->name = $fullName;

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

        return redirect()->route('user.profile')->with('success', 'Personal details saved.');
    }

    /**
     * NEW: Business Profile form from dashboard
     * Saves into user_business_profiles and triggers seller verification = pending.
     */
    public function updateBusinessProfile(Request $request)
    {
        $user = $request->user();

        $resolvedCountry = $this->normalizeBusinessProfileCountry(
            $request->input('country_code'),
            session('country', 'IN')
        );

        $profileConfig = $this->businessProfileCountryConfig($resolvedCountry);
        $supportedCountries = config('countries.supported', []);
        $resolvedCountryCurrency = $supportedCountries[$resolvedCountry]['currency'] ?? config('countries.default_currency', 'INR');

        $rules = [
            'company_name' => ['required', 'string', 'max:180'],
            'company_logo_path' => ['nullable', 'string', 'max:2048'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_address' => ['nullable', 'string', 'max:255'],

            // Other fields for potential UserBusinessProfile or future growth
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
            // Tax Info
            'tax_id_type' => ['nullable', 'string', 'max:20'], // e.g GST
            'tax_id_number' => ['nullable', 'string', 'max:50'],
            'tax_aadhaar' => ['nullable', 'string', 'max:20'],
            'tax_pan' => ['nullable', 'string', 'max:20'],
            'tax_iec' => ['nullable', 'string', 'max:30'],
            'tax_tan' => ['nullable', 'string', 'max:30'],
            'tax_vat' => ['nullable', 'string', 'max:30'],
            // Document URLs
            'doc_gst' => ['nullable', 'string', 'max:2048'],
            'doc_aadhaar' => ['nullable', 'string', 'max:2048'],
            'doc_pan' => ['nullable', 'string', 'max:2048'],
            'doc_iec' => ['nullable', 'string', 'max:2048'],
        ];

        if ($request->has('form_type')) {
            $formType = $request->input('form_type');

            if ($formType === 'additional') {
                $request->validate([
                    'additional_info.video_url' => ['nullable', 'url', 'max:2048'],
                ]);
            }

            $businessProfile = UserBusinessProfile::firstOrCreate(['user_id' => $user->id]);
            $existingInfo = $businessProfile->additional_info ?? [];

            // Define which keys are expected for each form type to handle empty/removed fields correctly
            $expectedKeys = [];
            if ($formType === 'additional') {
                $expectedKeys = [
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
                    'preferred_cities'
                ];
            } elseif ($formType === 'certification') {
                $expectedKeys = [
                    'certifications',
                    'awards',
                    'standard_certification',
                    'membership_affiliations',
                    'award_recognition'
                ];
            }

            $inputInfo = $request->input('additional_info', []);

            // Start with existing info
            $mergedInfo = $existingInfo;

            // For each expected key of this form type, update it from input
            // If it's not in the input (like unchecked checkboxes), set it to null/empty array
            foreach ($expectedKeys as $key) {
                $val = $inputInfo[$key] ?? (in_array($key, ['photos', 'payment_modes', 'certifications', 'awards']) ? [] : null);
                $mergedInfo[$key] = $val;

                // Sync to dedicated columns if they exist on the model
                if ($key === 'photos') {
                    $businessProfile->company_photos = $val;
                } elseif (
                    in_array($key, [
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
                        'awards'
                    ])
                ) {
                    $businessProfile->{$key} = $val;
                }
            }

            $businessProfile->additional_info = $mergedInfo;
            $businessProfile->save();

            return redirect()->back()->with('success', 'Information updated successfully.');
        }

        $data = $request->validate($rules);

        // Attempt to match the provided city_name to an actual city_id in the database
        $matchedCityId = $data['city_id'] ?? null;
        if (!$matchedCityId && !empty($data['city_name'])) {
            $cityQuery = City::where('name', $data['city_name'])->where('is_active', true);
            if (!empty($data['state_name'])) {
                // optionally we could filter by state if needed, but city name is usually enough for a quick match
                // $cityQuery->whereHas('state', function($q) use ($data){ $q->where('name', $data['state_name']); });
            }
            $matchedCity = $cityQuery->first();
            if ($matchedCity) {
                $matchedCityId = $matchedCity->id;
            }
        }

        // Update User Model directly (Alignment with provided schema)
        $user->company_name = $data['company_name'];
        $user->company_phone = $data['company_phone'] ?? null;
        $user->company_address = $data['company_address'] ?? null;
        $user->tax_id_type = $data['tax_id_type'] ?? null;
        $user->tax_id_number = $data['tax_id_number'] ?? null;
        $user->city_id = $matchedCityId ?? $user->city_id; // Use matched ID, or keep old if exists
        $user->pincode = $data['pincode'] ?? null;
        $user->country_code = $data['country_code'] ?? $resolvedCountry;

        // Trigger seller verification pending if needed
        if (in_array($user->seller_status, [User::SELLER_STATUS_NONE, User::SELLER_STATUS_REJECTED], true)) {
            $user->seller_status = User::SELLER_STATUS_PENDING;
            $user->seller_submitted_at = now();
            $user->seller_verified_at = null;
            $user->seller_rejection_reason = null;
        }

        $user->save();

        // Optional: Keep UserBusinessProfile in sync if it exists or intended for extra fields
        UserBusinessProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $data['company_name'],
                'company_logo_path' => $data['company_logo_path'] ?? null,
                'company_phone' => $data['company_phone'] ?? null,
                'fax_number' => $data['fax_number'] ?? null,
                'ownership_type' => $data['ownership_type'] ?? null,
                'annual_turnover_currency' => $data['annual_turnover_currency'] ?? $resolvedCountryCurrency,
                'annual_turnover_amount' => $data['annual_turnover_amount'] ?? null,
                'annual_turnover_unit' => $data['annual_turnover_unit'] ?? null,
                'year_established' => $data['year_established'] ?? null,
                'employee_count' => $data['employee_count'] ?? null,

                // manual address field
                'address_line' => $data['company_address'] ?? null,

                // automatically lookup city id or keep old dropdown (kept)
                'city_id' => $matchedCityId ?? $data['city_id'] ?? null,

                // NEW: text fallbacks + Google-derived values (safe + optional)
                'city_name' => $data['city_name'] ?? null,
                'state_name' => $data['state_name'] ?? null,
                'country_code' => $data['country_code'] ?? $resolvedCountry,
                'pincode' => $data['pincode'] ?? null,

                // NEW: Google Places fields
                'place_id' => $data['place_id'] ?? null,
                'formatted_address' => $data['formatted_address'] ?? null,
                'lat' => $data['lat'] ?? null,
                'lng' => $data['lng'] ?? null,


                // Dynamic-friendly tax/document storage
                'tax_ids_json' => array_filter([
                    'tax_id_type' => $data['tax_id_type'] ?? null,
                    'tax_id_number' => $data['tax_id_number'] ?? null,
                    'aadhaar' => $data['tax_aadhaar'] ?? null,
                    'pan' => $data['tax_pan'] ?? null,
                    'iec' => $data['tax_iec'] ?? null,
                    'tan' => $data['tax_tan'] ?? null,
                    'vat' => $data['tax_vat'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''),

                // Store all Documents in documents_json
                'documents_json' => array_filter([
                    'gst' => $data['doc_gst'] ?? null,
                    'aadhaar' => $data['doc_aadhaar'] ?? null,
                    'pan' => $data['doc_pan'] ?? null,
                    'iec' => $data['doc_iec'] ?? null,
                ], fn($value) => !is_null($value) && $value !== ''),
            ]
        );

        return redirect()->route('user.business-profile')->with('success', 'Business profile updated successfully.');
    }

    /**
     * NEW: Bank Details form from dashboard
     */
    public function updateBankDetails(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'account_type'           => ['nullable', 'string', 'max:30'],
            'account_holder_name'    => ['required', 'string', 'max:120'],
            'account_number'         => ['required', 'digits_between:9,18'],
            'confirm_account_number' => ['required', 'same:account_number'],
            'ifsc_code'              => ['required', 'string', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'linked_mobile'          => ['required', 'digits:10'],
        ], [
            'account_holder_name.required'    => 'Account holder name is required.',
            'account_number.required'         => 'Account number is required.',
            'account_number.digits_between'   => 'Account number must be between 9 and 18 digits.',
            'confirm_account_number.required' => 'Please confirm your account number.',
            'confirm_account_number.same'     => 'Account numbers do not match.',
            'ifsc_code.required'              => 'IFSC code is required.',
            'ifsc_code.regex'                 => 'Invalid IFSC code format (e.g. SBIN0001234).',
            'linked_mobile.required'          => 'Linked mobile number is required.',
            'linked_mobile.digits'            => 'Mobile number must be exactly 10 digits.',
        ]);



        UserBankDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'account_type' => $data['account_type'] ?? null,
                'account_holder_name' => $data['account_holder_name'] ?? null,
                'account_number' => $data['account_number'] ?? null,
                'ifsc_code' => $data['ifsc_code'] ?? null,
                'linked_mobile' => $data['linked_mobile'] ?? null,
            ]
        );

        return redirect()->route('user.bank-details')->with('success', 'Bank details saved.');
    }

    /**
     * OLD: keep existing Phase-1 update method untouched for now
     * (used by earlier simple profile form)
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $country = session('country', 'IN');

        $rules = [
            'name' => ['required', 'string', 'max:120'],
        ];

        if ($country === 'IN') {
            $rules['city_id'] = ['required', 'integer', 'exists:cities,id'];
        } else {
            $rules['city_id'] = ['nullable', 'integer', 'exists:cities,id'];
        }

        $rules['address'] = ['nullable', 'string', 'max:255'];

        $data = $request->validate($rules);


        $user->name = $data['name'];
        $user->address = $data['address'] ?? null;

        if ($country === 'IN') {
            $city = City::where('id', $data['city_id'])
                ->where('country_code', 'IN')
                ->where('is_active', true)
                ->firstOrFail();

            $user->city_id = $city->id;
            $user->pincode = $city->pincode;
        } else {
            $user->city_id = null;
            $user->pincode = null;
        }

        $user->save();


        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * DELETE: Permanently delete the user account and all associated data.
     */
    public function destroyAccount(Request $request)
    {
        $user = $request->user();

        DB::beginTransaction();
        try {
            // 1. Delete Product Inquiries (where user is buyer or seller)
            ProductInquiry::where('buyer_user_id', $user->id)
                ->orWhere('seller_user_id', $user->id)
                ->delete();

            // 2. Delete Support Tickets
            $user->supportTickets()->delete();
            $user->assignedReports()->delete();

            // 3. Delete Products and their specifications
            $user->products->each(function (Product $product) {
                $product->specifications()->delete();
                $product->delete();
            });

            // 4. Delete Business Profile and Bank Details
            $user->businessProfile()?->delete();
            $user->bankDetail()?->delete();

            // 5. Delete Notification Recipients
            $user->notificationRecipients()->delete();

            // 6. Finally delete the user
            $user->delete();

            DB::commit();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Your account has been permanently deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete account. Please try again.');
        }
    }
}