<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\UserTokenAuth;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class MobileMarketplaceController extends Controller
{
    public function config()
    {
        $countries = collect(config('countries.supported', []))
            ->filter(fn(array $country) => !empty($country['live']))
            ->map(function (array $country, string $code) {
                return [
                    'code' => $code,
                    'name' => $country['name'],
                    'currency' => $country['currency'],
                    'city_selector' => (bool) ($country['city_selector'] ?? false),
                    'homepage_route' => (bool) ($country['homepage_route'] ?? false),
                ];
            })
            ->values();

        return $this->jsonResponse([
            'default_country' => strtoupper(config('countries.default_country', 'IN')),
            'default_currency' => strtoupper(config('countries.default_currency', 'INR')),
            'countries' => $countries,
        ]);
    }

    public function home(Request $request)
    {
        $country = $this->resolveCountry($request->input('country'));
        $config = $this->countryConfig($country);
        $isIndiaLayout = (bool) ($config['homepage_route'] ?? false);

        $approvedProducts = Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id', 'left outer')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->with(['category', 'subcategory', 'user.businessProfile']);

        if (!$isIndiaLayout) {
            $approvedProducts->where('products.country_code', $country);
        }

        $featuredProducts = (clone $approvedProducts)
            ->latest('products.id')
            ->take($isIndiaLayout ? 10 : 12)
            ->get();

        $suppliersQuery = User::query()
            ->select('users.*')
            ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->with(['city', 'businessProfile']);

        if (!$isIndiaLayout) {
            $suppliersQuery->where('ubp.country_code', $country);
        }

        $suppliers = $suppliersQuery
            ->latest('users.seller_verified_at')
            ->take($isIndiaLayout ? 6 : 8)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->withCount([
                'products as product_count' => function ($query) use ($country, $isIndiaLayout) {
                    $query->whereHas('user', function ($userQuery) {
                        $userQuery->where('seller_status', User::SELLER_STATUS_APPROVED);
                    });

                    if (!$isIndiaLayout) {
                        $query->where('country_code', $country);
                    }
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take($isIndiaLayout ? 12 : 8)
            ->get();

        return $this->jsonResponse([
            'country' => [
                'code' => $country,
                'name' => $config['name'],
                'currency' => $config['currency'],
                'city_selector' => (bool) ($config['city_selector'] ?? false),
                'homepage_route' => $isIndiaLayout,
            ],
            'layout' => $isIndiaLayout ? 'india' : 'country',
            'hero' => $this->heroData($country, $config['name'], $isIndiaLayout),
            'highlights' => [
                ['title' => 'Trusted Sellers', 'subtitle' => 'Verified profiles'],
                ['title' => 'Quick Quotes', 'subtitle' => 'Fast response'],
                ['title' => 'Best Prices', 'subtitle' => 'Compare offers'],
            ],
            'quick_stats' => [
                'categories_count' => $categories->count(),
                'products_count' => $featuredProducts->count(),
                'suppliers_count' => $suppliers->count(),
            ],
            'categories' => $categories->map(fn(Category $category) => $this->transformCategory($category))->values(),
            'featured_products' => $featuredProducts->map(fn(Product $product) => $this->transformProduct($product))->values(),
            'top_suppliers' => $suppliers->map(fn(User $supplier) => $this->transformSupplier($supplier))->values(),
        ]);
    }

    public function categories(Request $request)
    {
        $country = $this->resolveCountry($request->input('country'));
        $isIndiaLayout = (bool) ($this->countryConfig($country)['homepage_route'] ?? false);

        $categories = Category::query()
            ->where('is_active', true)
            ->with([
                'subcategories' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                },
            ])
            ->withCount([
                'products as product_count' => function ($query) use ($country, $isIndiaLayout) {
                    $query->whereHas('user', function ($userQuery) {
                        $userQuery->where('seller_status', User::SELLER_STATUS_APPROVED);
                    });

                    if (!$isIndiaLayout) {
                        $query->where('country_code', $country);
                    }
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $this->jsonResponse([
            'categories' => $categories->map(function (Category $category) {
                return [
                    ...$this->transformCategory($category),
                    'subcategories' => $category->subcategories->map(function (Subcategory $subcategory) {
                        return [
                            'id' => $subcategory->id,
                            'name' => $subcategory->name,
                            'image_url' => $subcategory->image,
                            'icon_url' => $subcategory->icon,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    public function categoryDetail(Request $request, Category $category)
    {
        $country = $this->resolveCountry($request->input('country'));
        $isIndiaLayout = (bool) ($this->countryConfig($country)['homepage_route'] ?? false);

        $category = Category::query()
            ->where('is_active', true)
            ->whereKey($category->id)
            ->with([
                'subcategories' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                },
            ])
            ->firstOrFail();

        $subcategories = $category->subcategories->map(function (Subcategory $subcategory) use ($country, $isIndiaLayout) {
            $productCount = Product::query()
                ->where('category_id', $subcategory->category_id)
                ->where('subcategory_id', $subcategory->id)
                ->whereHas('user', function ($userQuery) {
                    $userQuery->where('seller_status', User::SELLER_STATUS_APPROVED);
                });

            if (!$isIndiaLayout) {
                $productCount->where('country_code', $country);
            }

            return [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'image_url' => $subcategory->image,
                'icon_url' => $subcategory->icon,
                'product_count' => $productCount->count(),
            ];
        })->values();

        return $this->jsonResponse([
            'category' => [
                ...$this->transformCategory($category),
                'subcategories' => $subcategories,
            ],
        ]);
    }

    public function products(Request $request)
    {
        $country = $this->resolveCountry($request->input('country'));
        $isIndiaLayout = (bool) ($this->countryConfig($country)['homepage_route'] ?? false);

        $query = Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->with(['category', 'subcategory', 'user.businessProfile']);

        if (!$isIndiaLayout) {
            $query->where('products.country_code', $country);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($productQuery) use ($search) {
                $productQuery->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.description', 'like', "%{$search}%")
                    ->orWhere('products.keywords', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('products.category_id', (int) $request->input('category_id'));
        }

        if ($request->filled('subcategory_id')) {
            $query->where('products.subcategory_id', (int) $request->input('subcategory_id'));
        }

        $perPage = max(1, min(30, (int) $request->input('per_page', 20)));
        $products = $query->latest('products.id')->paginate($perPage);

        return $this->jsonResponse([
            'data' => collect($products->items())->map(fn(Product $product) => $this->transformProduct($product))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function showProduct(Request $request, Product $product)
    {
        $product = Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('products.id', $product->id)
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->with(['category', 'subcategory', 'user.businessProfile'])
            ->firstOrFail();

        $relatedProducts = Product::query()
            ->select('products.*')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->where('products.id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('products.category_id', $product->category_id);

                if (!empty($product->subcategory_id)) {
                    $query->orWhere('products.subcategory_id', $product->subcategory_id);
                }
            })
            ->with(['category', 'subcategory', 'user.businessProfile'])
            ->latest('products.id')
            ->take(8)
            ->get();

        return $this->jsonResponse([
            'product' => $this->transformProduct($product, true),
            'related_products' => $relatedProducts->map(fn(Product $relatedProduct) => $this->transformProduct($relatedProduct))->values(),
        ]);
    }

    public function submitInquiry(Request $request, Product $product)
    {
        $token = UserTokenAuth::resolveTokenFromRequest($request);
        $apiUser = $token?->user;

        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:1500'],
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        if (!$apiUser && empty($data['phone']) && empty($data['email'])) {
            return response()->json([
                'message' => 'Phone or email is required for guest inquiry.',
            ], 422);
        }

        ProductInquiry::create([
            'product_id' => $product->id,
            'buyer_user_id' => $apiUser?->id,
            'seller_user_id' => $product->user_id,
            'name' => $apiUser?->name ?? ($data['name'] ?? null),
            'email' => $apiUser?->email ?? ($data['email'] ?? null),
            'phone' => $apiUser?->phone ?? ($data['phone'] ?? null),
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Inquiry sent successfully.',
        ]);
    }

    protected function resolveCountry(?string $countryCode): string
    {
        $supported = config('countries.supported', []);
        $default = strtoupper(config('countries.default_country', 'IN'));
        $country = strtoupper(trim((string) $countryCode));

        return isset($supported[$country]) ? $country : $default;
    }

    protected function countryConfig(string $country): array
    {
        $supported = config('countries.supported', []);
        $defaultCountry = strtoupper(config('countries.default_country', 'IN'));

        return $supported[$country] ?? $supported[$defaultCountry];
    }

    protected function heroData(string $country, string $countryName, bool $isIndiaLayout): array
    {
        if ($isIndiaLayout) {
            return [
                'title' => 'Find Products & Verified Suppliers',
                'subtitle' => 'Get quotes, compare prices, and grow your business.',
                'usp' => 'Trusted sellers • Quick quotes • Better prices',
                'primary_cta' => 'Explore Products',
                'secondary_cta' => 'Post Requirement',
            ];
        }

        $countryHeroes = [
            'AE' => 'Source Products from UAE & Global Suppliers',
            'US' => 'Find Trusted US Manufacturers & Exporters',
            'UK' => 'Buy & Sell with Verified UK Suppliers',
            'CA' => 'Discover Reliable Canadian Suppliers',
            'AU' => 'Connect with Leading Australian Suppliers',
            'CN' => 'Discover Verified Chinese Manufacturers & Exporters',
        ];

        return [
            'title' => $countryHeroes[$country] ?? 'Explore Products from ' . $countryName,
            'subtitle' => 'Discover country-specific products and verified suppliers.',
            'usp' => 'Verified sellers • Country-focused products • Fast inquiry',
            'primary_cta' => 'Explore Products',
            'secondary_cta' => 'Post Requirement',
        ];
    }

    protected function transformCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'image_url' => $category->image,
            'icon_url' => $category->icon,
            'product_count' => (int) ($category->product_count ?? 0),
        ];
    }

    protected function transformSupplier(User $supplier): array
    {
        $businessProfile = $supplier->businessProfile;

        return [
            'id' => $supplier->id,
            'name' => $businessProfile?->company_name ?? $supplier->company_name ?? $supplier->name,
            'contact_name' => $supplier->name,
            'city_name' => $businessProfile?->city_name ?? $supplier->city?->name,
            'state_name' => $businessProfile?->state_name,
            'country_code' => strtoupper($businessProfile?->country_code ?? $supplier->country_code ?? config('countries.default_country', 'IN')),
            'company_name' => $businessProfile?->company_name ?? $supplier->company_name,
            'ownership_type' => $businessProfile?->ownership_type,
            'year_established' => $businessProfile?->year_established,
            'profile_image_url' => $supplier->profile_photo_path,
            'verified' => $supplier->seller_status === User::SELLER_STATUS_APPROVED,
        ];
    }

    protected function transformProduct(Product $product, bool $withDescription = false): array
    {
        $businessProfile = $product->user?->businessProfile;
        $images = is_array($product->images) ? $product->images : [];

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $withDescription ? $product->description : null,
            'image_url' => $images[0] ?? null,
            'images' => $images,
            'price' => is_null($product->price) ? null : (float) $product->price,
            'currency' => strtoupper($product->currency ?? config('countries.default_currency', 'INR')),
            'country_code' => strtoupper($product->country_code ?? config('countries.default_country', 'IN')),
            'min_order_quantity' => (int) ($product->min_order_quantity ?? 1),
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'subcategory' => $product->subcategory ? [
                'id' => $product->subcategory->id,
                'name' => $product->subcategory->name,
            ] : null,
            'supplier' => [
                'id' => $product->user?->id,
                'name' => $businessProfile?->company_name ?? $product->user?->company_name ?? $product->user?->name ?? 'Verified Seller',
                'contact_name' => $product->user?->name,
                'city_name' => $businessProfile?->city_name,
                'state_name' => $businessProfile?->state_name,
                'country_code' => strtoupper($businessProfile?->country_code ?? $product->user?->country_code ?? $product->country_code ?? config('countries.default_country', 'IN')),
                'verified' => $product->user?->seller_status === User::SELLER_STATUS_APPROVED,
            ],
        ];
    }

    protected function jsonResponse(array $payload)
    {
        return response()->json(
            $payload,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}
