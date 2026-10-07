<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductSpecification;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;



class ProductController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category');
        $subcategoryId = $request->input('subcategory');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $verifiedOnly = $request->boolean('verified');
        $moq = $request->input('moq', []);
        $locations = $request->input('location', []);
        $btypes = $request->input('btype', []);
        $sort = $request->input('sort', 'newest');
        $sessionCity = trim((string) session('city_name', ''));

        $supportedCountries = config('countries.supported', []);
        $defaultCountry = strtoupper(config('countries.default_country', 'IN'));

        $requestedCountry = strtoupper((string) $request->input('country', ''));
        $sessionCountry = strtoupper((string) session('country', $defaultCountry));

        $activeCountry = $requestedCountry !== '' ? $requestedCountry : $sessionCountry;
        if (!isset($supportedCountries[$activeCountry])) {
            $activeCountry = $defaultCountry;
        }

        $isIndiaMode = $activeCountry === 'IN';

        $selectedCategory = $categoryId ? Category::find($categoryId) : null;
        $selectedSubcategory = $subcategoryId ? Subcategory::find($subcategoryId) : null;

        // Base query WITHOUT price filter — used to determine real max price from DB
        $baseQuery = Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->where('products.status', Product::STATUS_LIVE);

        $sessionCity = strtolower(trim((string) session('city_name', '')));
        $sessionCityCountry = strtoupper(trim((string) session('city_country_code', $activeCountry)));
        $currentCountry = $activeCountry;

        if ($categoryId) {
            $baseQuery->where('products.category_id', $categoryId);
        }

        if ($subcategoryId) {
            $baseQuery->where('products.subcategory_id', $subcategoryId);
        }

        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%")
                    ->orWhere('products.keywords', 'like', "%{$search}%");
            });
        }

        if ($verifiedOnly) {
            $baseQuery->whereNotNull('users.seller_verified_at');
        }

        if (!empty($moq)) {
            $baseQuery->where(function ($q) use ($moq) {
                foreach ($moq as $m) {
                    if ($m === '') {
                        $q->orWhereNull('products.min_order_quantity')->orWhere('products.min_order_quantity', '<=', 1);
                    } elseif ($m === 'moq_10') {
                        $q->orWhereBetween('products.min_order_quantity', [2, 10]);
                    } elseif ($m === 'moq_100') {
                        $q->orWhereBetween('products.min_order_quantity', [11, 100]);
                    } elseif ($m === 'moq_500') {
                        $q->orWhereBetween('products.min_order_quantity', [101, 500]);
                    } elseif ($m === 'moq_plus') {
                        $q->orWhere('products.min_order_quantity', '>', 500);
                    }
                }
            });
        }

        if (!empty($locations)) {
            $baseQuery->where(function ($q) use ($locations) {
                foreach ($locations as $loc) {
                    $q->orWhere('ubp.state_name', 'like', "%$loc%")
                        ->orWhere('ubp.city_name', 'like', "%$loc%");
                }
            });
        }

        if (!empty($btypes)) {
            $baseQuery->where(function ($q) use ($btypes) {
                foreach ($btypes as $bt) {
                    $q->orWhere('ubp.ownership_type', 'like', "%$bt%");
                }
            });
        }

        // Dynamic max price from DB (min is always 0)
        $dbMaxPrice = (int) ($baseQuery->clone()->max('products.price') ?? 100000);
        $dbMaxPrice = $dbMaxPrice > 0 ? $dbMaxPrice : 100000;

        // Filtered query (clone base, then apply price filters)
        $query = clone $baseQuery;

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('products.price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('products.price', '<=', (float) $maxPrice);
        }

        // City-first ordering only for India/global mode
        $query->orderByRaw(
            "CASE
        WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
        WHEN products.country_code = ? THEN 1
        ELSE 2
    END",
            [
                $sessionCity,
                $sessionCityCountry,
                $currentCountry,
                $sessionCity,
                $currentCountry,
            ]
        );

        // Apply Sorting
        if ($sort === 'price_asc') {
            $query->orderBy('products.price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('products.price', 'desc');
        } else {
            $query->orderByDesc('products.created_at');
        }

        $products = $query->with(['user.businessProfile', 'category', 'subcategory'])
            ->paginate(12)
            ->withQueryString();

        $totalCount = $query->toBase()->getCountForPagination();

        // Subcategories for sidebar filter
        $subcategories = $categoryId
            ? Subcategory::where('category_id', $categoryId)->where('is_active', true)->orderBy('name')->get()
            : collect();

        // All categories for sidebar
        $allCategories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.products', compact(
            'products',
            'selectedCategory',
            'selectedSubcategory',
            'subcategories',
            'allCategories',
            'totalCount',
            'dbMaxPrice'
        ))->with([
                    'activeCountry' => $activeCountry,
                    'isIndiaMode' => $isIndiaMode,
                ]);
    }

    public function allCategories()
    {
        $categories = Category::where('is_active', true)
            ->with([
                'subcategories' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->take(5);
                }
            ])
            ->orderBy('sort_order')
            ->get();

        return view('pages.categories', compact('categories'));
    }

    public function categoryDetail($id)
    {
        $category = Category::where('is_active', true)->findOrFail($id);

        // All active subcategories with product count
        $subcategories = Subcategory::where('category_id', $category->id)
            ->where('is_active', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        // Products grouped by subcategory (8 per subcategory)
        $productsBySubcategory = [];
        foreach ($subcategories as $sub) {
            $products = Product::publicVisible()
                ->where('category_id', $category->id)
                ->where('subcategory_id', $sub->id)
                ->with(['user.businessProfile'])
                ->latest()
                ->take(8)
                ->get();
            if ($products->isNotEmpty()) {
                $productsBySubcategory[$sub->id] = [
                    'subcategory' => $sub,
                    'products' => $products,
                ];
            }
        }

        // Products without subcategory
        $uncategorizedProducts = Product::publicVisible()
            ->where('category_id', $category->id)
            ->whereNull('subcategory_id')
            ->with(['user.businessProfile'])
            ->latest()
            ->take(8)
            ->get();

        // All other categories for sidebar "Top Categories"
        $allCategories = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->get();

        return view('pages.category_detail', compact(
            'category',
            'subcategories',
            'productsBySubcategory',
            'uncategorizedProducts',
            'allCategories'
        ));
    }


    public function show($id)
    {
        $product = \App\Models\Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', $id)
            ->where('products.status', Product::STATUS_LIVE)
            ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
            ->with([
                'category',
                'subcategory',
                'specifications',
                'user.businessProfile',
            ])
            ->firstOrFail();

        $relatedProducts = \App\Models\Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
            ->where('products.status', Product::STATUS_LIVE)
            ->where('products.id', '!=', $product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('products.category_id', $product->category_id);
            }, function ($query) use ($product) {
                $query->where('products.subcategory_id', $product->subcategory_id);
            })
            ->with(['user.businessProfile'])
            ->latest('products.id')
            ->take(8)
            ->get();

        return view('pages.product_detail', compact('product', 'relatedProducts'));
    }

    public function myProducts(Request $request)
    {
        $user = $request->user();
        $status = trim((string) $request->query('status', ''));

        $query = Product::where('user_id', $user->id)
            ->with(['category', 'subcategory'])
            ->latest();

        if (in_array($status, [Product::STATUS_PENDING, Product::STATUS_LIVE], true)) {
            $query->where('status', $status);
        }

        $products = $query->paginate(10)->withQueryString();

        return view('user.products.index', [
            'user' => $user,
            'products' => $products,
            'currentStatus' => $status,
        ]);
    }

    public function exportMyProducts(Request $request): StreamedResponse
    {
        $user = $request->user();
        $status = trim((string) $request->query('status', ''));

        $query = Product::query()
            ->where('user_id', $user->id)
            ->with(['category:id,name', 'subcategory:id,name'])
            ->latest('id');

        if (in_array($status, [Product::STATUS_PENDING, Product::STATUS_LIVE], true)) {
            $query->where('status', $status);
        }

        $rows = $query->get();
        $stamp = now()->format('Ymd_His');
        $statusLabel = $status !== '' ? $status : 'all';
        $fileName = 'my_products_' . $statusLabel . '_' . $stamp . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Product ID',
                'Status',
                'Product Name',
                'Category',
                'Subcategory',
                'Country',
                'Currency',
                'Price',
                'MOQ',
                'Created At',
            ]);

            foreach ($rows as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->status,
                    $product->name,
                    $product->category?->name,
                    $product->subcategory?->name,
                    strtoupper((string) $product->country_code),
                    strtoupper((string) $product->currency),
                    $product->price,
                    $product->min_order_quantity,
                    optional($product->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importMyProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Unable to read the uploaded file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'The uploaded file is empty.');
        }

        $header = array_map(fn($col) => strtolower(trim((string) $col)), $header);

        $nameIdx = array_search('product name', $header) !== false ? array_search('product name', $header) : array_search('name', $header);
        $categoryIdx = array_search('category', $header) !== false ? array_search('category', $header) : array_search('category name', $header);
        $subcategoryIdx = array_search('subcategory', $header) !== false ? array_search('subcategory', $header) : array_search('subcategory name', $header);
        $priceIdx = array_search('price', $header);
        $moqIdx = array_search('moq', $header) !== false ? array_search('moq', $header) : array_search('min order quantity', $header);
        $descriptionIdx = array_search('description', $header);

        if ($nameIdx === false || $categoryIdx === false || $priceIdx === false || $descriptionIdx === false) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV format. Required columns: Product Name, Category, Price, Description.');
        }

        $imported = 0;
        $user = $request->user();
        $sessionCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
        $resolvedCountry = strtoupper($user->businessProfile->country_code ?? $user->country_code ?? $sessionCountry);
        $supportedCountries = config('countries.supported', []);
        $resolvedCurrency = strtoupper($supportedCountries[$resolvedCountry]['currency'] ?? config('countries.default_currency', 'INR'));

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[$nameIdx] ?? '');
            $categoryName = trim($row[$categoryIdx] ?? '');
            $subcategoryName = trim($row[$subcategoryIdx] ?? '');
            $price = floatval(trim($row[$priceIdx] ?? 0));
            $moq = intval(trim($row[$moqIdx] ?? 1));
            $description = trim($row[$descriptionIdx] ?? '');

            if ($name === '' || $categoryName === '') {
                continue;
            }

            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['is_active' => true, 'sort_order' => 0]
            );

            $subcategoryId = null;
            if ($subcategoryName !== '') {
                $sub = Subcategory::firstOrCreate(
                    ['category_id' => $category->id, 'name' => $subcategoryName],
                    ['is_active' => true, 'sort_order' => 0]
                );
                $subcategoryId = $sub->id;
            }

            Product::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'subcategory_id' => $subcategoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'currency' => $resolvedCurrency,
                'country_code' => $resolvedCountry,
                'min_order_quantity' => $moq > 0 ? $moq : 1,
                'images' => [],
                'status' => Product::STATUS_PENDING,
            ]);

            $imported++;
        }

        fclose($handle);

        return back()->with('success', "Successfully imported {$imported} products. They are now under review.");
    }

    public function myCatalog(Request $request)
    {
        $user = $request->user();

        $products = Product::where('user_id', $user->id)
            ->with(['category', 'subcategory'])
            ->latest()
            ->paginate(12);

        return view('user.products.catalog', [
            'user' => $user,
            'products' => $products,
        ]);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->with([
                'subcategories' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        return view('user.products.create', [
            'user' => auth()->user(),
            'categories' => $categories,
            'initialSpecifications' => old('spec_keys')
                ? collect(old('spec_keys', []))->map(function ($key, $index) {
                    return [
                        'key' => $key,
                        'value' => old('spec_values.' . $index),
                    ];
                })->values()->all()
                : [],
            'shippingOptionsList' => [
                'Air Freight',
                'Sea Freight',
                'Road Transport',
                'Rail Transport',
                'Courier / Parcel',
                'Local Delivery',
                'Pickup Available',
            ],
            'paymentOptionsList' => [
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
            'supportOptionsList' => [
                'OEM',
                'ODM',
                'OEM / ODM',
                'Private Label',
                'Standard Supply Only',
            ],
            'leadTimeUnits' => [
                'Days',
                'Business Days',
                'Weeks',
            ],
        ]);
    }

    public function edit(Product $product)
    {
        $this->ensureOwner($product);

        $categories = Category::where('is_active', true)
            ->with([
                'subcategories' => function ($q) {
                    $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        return view('user.products.edit', [
            'user' => auth()->user(),
            'product' => $product->load('specifications'),
            'categories' => $categories,
            'initialSpecifications' => old('spec_keys')
                ? collect(old('spec_keys', []))->map(function ($key, $index) {
                    return [
                        'key' => $key,
                        'value' => old('spec_values.' . $index),
                    ];
                })->values()->all()
                : $product->specifications->map(function ($spec) {
                    return [
                        'key' => $spec->spec_key,
                        'value' => $spec->spec_value,
                    ];
                })->values()->all(),
            'shippingOptionsList' => [
                'Air Freight',
                'Sea Freight',
                'Road Transport',
                'Rail Transport',
                'Courier / Parcel',
                'Local Delivery',
                'Pickup Available',
            ],
            'paymentOptionsList' => [
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
            'supportOptionsList' => [
                'OEM',
                'ODM',
                'OEM / ODM',
                'Private Label',
                'Standard Supply Only',
            ],
            'leadTimeUnits' => [
                'Days',
                'Business Days',
                'Weeks',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'moq' => ['nullable', 'integer', 'min:1'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['nullable', 'url'],
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

        $user = $request->user();
        $businessProfile = $user?->businessProfile;

        $sessionCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
        $resolvedCountry = strtoupper(
            $businessProfile->country_code
            ?? $user->country_code
            ?? $sessionCountry
            ?? config('countries.default_country', 'IN')
        );

        $supportedCountries = config('countries.supported', []);
        if (!isset($supportedCountries[$resolvedCountry])) {
            $resolvedCountry = config('countries.default_country', 'IN');
        }

        $resolvedCurrency = strtoupper(
            $supportedCountries[$resolvedCountry]['currency']
            ?? config('countries.default_currency', 'INR')
        );

        $specRows = $this->normalizedSpecificationsFromRequest($request);

        DB::transaction(function () use ($validated, $user, $resolvedCurrency, $resolvedCountry, $specRows) {
            $product = Product::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'price_to' => $validated['price_to'] ?? null,
                'currency' => $resolvedCurrency,
                'country_code' => $resolvedCountry,
                'min_order_quantity' => $validated['moq'] ?? 1,
                'images' => $validated['images'] ?? [],
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

        return redirect()
            ->route('user.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $this->ensureOwner($product);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_to' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'moq' => ['nullable', 'integer', 'min:1'],
            'images' => ['nullable', 'array', 'max:6'],
            'images.*' => ['nullable', 'url'],
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

        $specRows = $this->normalizedSpecificationsFromRequest($request);

        DB::transaction(function () use ($product, $validated, $specRows) {
            $product->update([
                'category_id' => $validated['category_id'],
                'subcategory_id' => $validated['subcategory_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'price_to' => $validated['price_to'] ?? null,
                'min_order_quantity' => $validated['moq'] ?? 1,
                'images' => $validated['images'] ?? [],
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

        return redirect()
            ->route('user.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->ensureOwner($product);

        $product->delete();

        return redirect()
            ->route('user.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    protected function normalizedSpecificationsFromRequest(Request $request): array
    {
        $keys = $request->input('spec_keys', []);
        $values = $request->input('spec_values', []);

        $keys = is_array($keys) ? array_values($keys) : [];
        $values = is_array($values) ? array_values($values) : [];

        $max = max(count($keys), count($values));
        $rows = [];

        for ($i = 0; $i < $max; $i++) {
            $key = trim((string) ($keys[$i] ?? ''));
            $value = trim((string) ($values[$i] ?? ''));

            if ($key === '' && $value === '') {
                continue;
            }

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

    protected function ensureOwner(Product $product): void
    {
        abort_unless((int) $product->user_id === (int) Auth::id(), 403);
    }
}
