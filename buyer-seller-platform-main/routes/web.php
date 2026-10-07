<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserInquiriesController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\City;
use App\Http\Controllers\SellerProfileController;
use Illuminate\Http\Request;
use App\Http\Controllers\LocationPreferenceController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\UserSupportTicketController;


/*
|--------------------------------------------------------------------------
| Helper: India Home Data Loader
|--------------------------------------------------------------------------
| We keep this here to avoid forcing a controller refactor.
| Loads categories, featured products, and banners for India homepage.
*/
$indiaHomeData = function (string $activeCountry = 'IN') {
    $activeCountry = strtoupper($activeCountry);
    $sessionCity = strtolower(trim((string) session('city_name', '')));
    $sessionCityCountry = strtoupper(trim((string) session('city_country_code', $activeCountry)));

    $categories = Category::where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    $featuredProducts = Product::query()
        ->select('products.*')
        ->join('users', 'users.id', '=', 'products.user_id')
        ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->where('products.status', \App\Models\Product::STATUS_LIVE)
        ->with(['category', 'subcategory', 'user.businessProfile'])
        ->orderByRaw(
            "CASE
                WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
                WHEN products.country_code = ? THEN 1
                ELSE 2
            END",
            [
                $sessionCity,
                $sessionCityCountry,
                $activeCountry,
                $sessionCity,
                $activeCountry,
            ]
        )
        ->latest('products.id')
        ->take(10)
        ->get();

    // Banner tables may not exist yet — keep safe
    $heroBanners = class_exists(Banner::class)
        ? Banner::where('country_code', $activeCountry)
            ->where('placement', 'hero')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
        : collect();

    $midBanners = class_exists(Banner::class)
        ? Banner::where('country_code', $activeCountry)
            ->where('placement', 'mid')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
        : collect();

    $bottomBanners = class_exists(Banner::class)
        ? Banner::where('country_code', $activeCountry)
            ->where('placement', 'bottom')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
        : collect();

    // Top suppliers with local city first, same country next, global fallback last
    $topSuppliers = \App\Models\User::query()
        ->select('users.*')
        ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->with(['city', 'businessProfile'])
        ->orderByRaw(
            "CASE
                WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
                WHEN UPPER(COALESCE(ubp.country_code, '')) = ? THEN 1
                ELSE 2
            END",
            [
                $sessionCity,
                $sessionCityCountry,
                $activeCountry,
                $sessionCity,
                $activeCountry,
            ]
        )
        ->latest('users.seller_verified_at')
        ->take(40)
        ->get();

    // Category rows with products ordered local city first, same country next, then global
    $categoryRows = \App\Models\Category::query()
        ->with([
            'subcategories' => function ($q) {
                $q->orderBy('sort_order')->orderBy('name');
            },
            'products' => function ($q) use ($sessionCity, $sessionCityCountry, $activeCountry) {
                $q->select('products.*')
                    ->join('users', 'users.id', '=', 'products.user_id')
                    ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
                    ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
                    ->where('products.status', \App\Models\Product::STATUS_LIVE)
                    ->with(['user.businessProfile'])
                    ->orderByRaw(
                        "CASE
                            WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
                            WHEN products.country_code = ? THEN 1
                            ELSE 2
                        END",
                        [
                            $sessionCity,
                            $sessionCityCountry,
                            $activeCountry,
                            $sessionCity,
                            $activeCountry,
                        ]
                    )
                    ->latest('products.id');
            },
        ])
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    return compact(
        'categories',
        'featuredProducts',
        'heroBanners',
        'midBanners',
        'bottomBanners',
        'topSuppliers',
        'categoryRows'
    );
};

/*
|--------------------------------------------------------------------------
| Homepage (India default)
|--------------------------------------------------------------------------
*/

Route::get('/clear-config', function() {
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    return "Config and Cache cleared successfully!";
});


Route::get('/', function () use ($indiaHomeData) {
    $defaultCountry = config('countries.default_country', 'IN');
    $country = strtoupper(session('country', $defaultCountry));
    $countries = config('countries.supported', []);

    if (!isset($countries[$country])) {
        $country = $defaultCountry;
    }

    if ($country !== 'IN') {
        return redirect('/' . strtolower($country));
    }

    return view('pages.home', array_merge(
        ['title' => 'Buyer Seller Platform - India'],
        $indiaHomeData('IN')
    ));
});

/*
|--------------------------------------------------------------------------
| Country Landing Pages
|--------------------------------------------------------------------------
*/
Route::get('/{country}', function ($country) use ($indiaHomeData) {
    $country = strtoupper($country);
    $countries = config('countries.supported', []);

    if (!isset($countries[$country]) || empty($countries[$country]['live'])) {
        abort(404);
    }

    $currency = $countries[$country]['currency'] ?? config('countries.default_currency', 'INR');

    session([
        'country' => $country,
        'currency' => $currency,
    ]);

    if ($country === 'IN') {
        return view('pages.home', array_merge(
            ['title' => 'Buyer Seller Platform - India'],
            $indiaHomeData('IN')
        ));
    }

    $sessionCity = strtolower(trim((string) session('city_name', '')));
    $sessionCityCountry = strtoupper(trim((string) session('city_country_code', $country)));

    $products = \App\Models\Product::query()
        ->select('products.*')
        ->join('users', 'users.id', '=', 'products.user_id')
        ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->where('products.status', \App\Models\Product::STATUS_LIVE)
        ->where(function ($query) use ($country) {
            $query->where('products.country_code', $country)
                ->orWhere(function ($nested) use ($country) {
                    $nested->whereNull('products.country_code')
                        ->whereRaw('UPPER(COALESCE(ubp.country_code, "")) = ?', [$country]);
                });
        })
        ->with(['category:id,name', 'subcategory:id,name', 'user:id,name', 'user.businessProfile'])
        ->orderByRaw(
            "CASE
            WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
            ELSE 1
        END",
            [
                $sessionCity,
                $sessionCityCountry,
                $country,
                $sessionCity,
            ]
        )
        ->latest('products.id')
        ->take(12)
        ->get();

    $suppliers = \App\Models\User::query()
        ->select('users.*')
        ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->whereRaw('UPPER(COALESCE(ubp.country_code, "")) = ?', [$country])
        ->with(['city', 'businessProfile'])
        ->orderByRaw(
            "CASE
            WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
            ELSE 1
        END",
            [
                $sessionCity,
                $sessionCityCountry,
                $country,
                $sessionCity,
            ]
        )
        ->latest('users.id')
        ->take(8)
        ->get();

    $otherInternationalProducts = \App\Models\Product::query()
        ->select('products.*')
        ->join('users', 'users.id', '=', 'products.user_id')
        ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->where('products.status', \App\Models\Product::STATUS_LIVE)
        ->where(function ($query) use ($country) {
            $query->where('products.country_code', '!=', $country)
                ->orWhere(function ($nested) use ($country) {
                    $nested->whereNull('products.country_code')
                        ->whereRaw('UPPER(COALESCE(ubp.country_code, "")) != ?', [$country]);
                });
        })
        ->with(['category:id,name', 'subcategory:id,name', 'user:id,name', 'user.businessProfile'])
        ->latest('products.id')
        ->take(12)
        ->get();

    $otherInternationalSuppliers = \App\Models\User::query()
        ->select('users.*')
        ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', \App\Models\User::SELLER_STATUS_APPROVED)
        ->whereRaw('UPPER(COALESCE(ubp.country_code, "")) != ?', [$country])
        ->with(['city', 'businessProfile'])
        ->latest('users.id')
        ->take(8)
        ->get();

    $popularCategories = \App\Models\Category::query()
        ->select('categories.id', 'categories.name', 'categories.image', 'categories.icon')
        ->whereHas('products', function ($q) use ($country) {
            $q->where('country_code', $country)
                ->where('status', \App\Models\Product::STATUS_LIVE)
                ->whereHas('user', function ($uq) {
                    $uq->where('seller_status', \App\Models\User::SELLER_STATUS_APPROVED);
                });
        })
        ->withCount([
            'products as country_products_count' => function ($q) use ($country) {
                $q->where('country_code', $country)
                    ->where('status', \App\Models\Product::STATUS_LIVE)
                    ->whereHas('user', function ($uq) {
                        $uq->where('seller_status', \App\Models\User::SELLER_STATUS_APPROVED);
                    });
            }
        ])
        ->orderByDesc('country_products_count')
        ->take(8)
        ->get();

    return view('pages.country_home', [
        'title' => 'Buyer Seller Platform - ' . ($countries[$country]['name'] ?? $country),
        'country' => $country,
        'countryName' => $countries[$country]['name'] ?? $country,
        'countryConfig' => $countries[$country],
        'products' => $products,
        'suppliers' => $suppliers,
        'otherInternationalProducts' => $otherInternationalProducts,
        'otherInternationalSuppliers' => $otherInternationalSuppliers,
        'popularCategories' => $popularCategories,
    ]);
})->where('country', '[A-Za-z]{2}');

/*
|--------------------------------------------------------------------------
| Country Switcher + City Selector/Switcher
|--------------------------------------------------------------------------
*/
Route::post('/set-country', [LocationPreferenceController::class, 'setCountry'])->name('set-country');
Route::post('/set-city', [LocationPreferenceController::class, 'setCity'])->name('set-city');
Route::post('/set-currency', [LocationPreferenceController::class, 'setCurrency'])->name('set-currency');
Route::post('/detect-location', [LocationPreferenceController::class, 'detectLocation'])->name('detect-location');
Route::post('/language/preference', [LocalizationController::class, 'setLanguagePreference'])->name('language.preference');
Route::post('/language/translate', [LocalizationController::class, 'translate'])->name('language.translate');

/*
|--------------------------------------------------------------------------
| Contact (UI + Basic Form)
|--------------------------------------------------------------------------
*/
Route::get('/acceptable-use-policy', function () {
    return view('pages.acceptable_use_policy', [
        'title' => 'Acceptable Use Policy - Buyer Seller Platform',
    ]);
})->name('acceptable-use-policy');

Route::get('/seller-verification-policy', function () {
    return view('pages.seller_verification_policy', [
        'title' => 'Seller Verification Policy - Buyer Seller Platform',
    ]);
})->name('seller-verification-policy');

Route::get('/copyright-policy', function () {
    return view('pages.copyright_policy', [
        'title' => 'Copyright Policy - Buyer Seller Platform',
    ]);
})->name('copyright-policy');

Route::get('/disclaimer', function () {
    return view('pages.disclaimer', [
        'title' => 'Disclaimer - Buyer Seller Platform',
    ]);
})->name('disclaimer');

Route::get('/refund-policy', function () {
    return view('pages.refund_policy', [
        'title' => 'Refund Policy - Buyer Seller Platform',
    ]);
})->name('refund-policy');

Route::get('/data-policy', function () {
    return view('pages.data_policy', [
        'title' => 'Data Usage & Retention Policy - Buyer Seller Platform',
    ]);
})->name('data-policy');

Route::get('/terms', function () {
    return view('pages.terms', [
        'title' => 'Terms & Conditions - Buyer Seller Platform',
    ]);
})->name('terms');

Route::get('/privacy-policy', function () {
    return view('pages.privacy_policy', [
        'title' => 'Privacy Policy - Buyer Seller Platform',
    ]);
})->name('privacy-policy');

Route::get('/contact', function () {
    return view('pages.contact', [
        'title' => 'Contact Us - Buyer Seller Platform',
    ]);
})->name('contact');

Route::get('/about', function () {
    return view('pages.about', [
        'title' => 'About Us - Buyer Seller Platform',
    ]);
})->name('about');

Route::post('/contact', function (Request $request) {
    $request->validate([
        'name' => ['required', 'string', 'max:80'],
        'email' => ['required', 'email', 'max:120'],
        'phone' => ['nullable', 'string', 'max:30'],
        'issue_type' => ['required', 'string', 'max:30'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    // UI-only for now (no DB/email yet) — safe and non-breaking
    return back()->with('success', 'Thanks! Your message has been received. We will contact you soon.');
})->name('contact.submit');


/*
|--------------------------------------------------------------------------
| Authentication (OTP)
|--------------------------------------------------------------------------
*/
Route::get('/login', [OtpAuthController::class, 'showLogin'])->name('login');
Route::post('/login/request-otp', [OtpAuthController::class, 'requestOtp'])->name('login.requestOtp');
Route::get('/verify-otp', [OtpAuthController::class, 'showVerifyOtp'])->name('login.verifyOtp');
Route::post('/verify-otp', [OtpAuthController::class, 'verifyOtp'])->name('login.verifyOtp.post');
Route::get('/auth/google/redirect', [OtpAuthController::class, 'redirectToGoogle'])->name('login.google.redirect');
Route::get('/auth/google/callback', [OtpAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::post('/logout', [OtpAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public Products
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [ProductController::class, 'allCategories'])->name('categories.all');
Route::get('/category/{id}', [ProductController::class, 'categoryDetail'])->name('category.detail');

/*
|--------------------------------------------------------------------------
| All Suppliers Listing
|--------------------------------------------------------------------------
*/
Route::get('/suppliers', function (\Illuminate\Http\Request $request) {

    $query = \App\Models\User::select('users.*')
        ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
        ->where('users.seller_status', 'approved')
        ->with(['city', 'businessProfile'])
        ->withCount('products');

    // Location-first ordering: city -> same country -> global fallback
    $sessionCity = strtolower(trim((string) session('city_name', '')));
    $sessionCityCountry = strtoupper(trim((string) session('city_country_code', session('country', config('countries.default_country', 'IN')))));
    $currentCountry = strtoupper(session('country', config('countries.default_country', 'IN')));

    $query->orderByRaw(
        "CASE
        WHEN ? <> '' AND ? = ? AND LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0
        WHEN UPPER(COALESCE(ubp.country_code, '')) = ? THEN 1
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

    // Search by name / company name
    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(function ($sq) use ($q) {
            $sq->where('users.name', 'like', "%{$q}%")
                ->orWhere('ubp.company_name', 'like', "%{$q}%")
                ->orWhere('ubp.city_name', 'like', "%{$q}%")
                ->orWhere('ubp.state_name', 'like', "%{$q}%");
        });
    }

    // Filter by business / ownership type (from business profile)
    if ($request->filled('type')) {
        $query->whereHas(
            'businessProfile',
            fn($sq) =>
            $sq->where('ownership_type', $request->type)
        );
    }

    // Filter by state/location
    if ($request->filled('state')) {
        $query->whereHas(
            'businessProfile',
            fn($sq) =>
            $sq->where('state_name', $request->state)
        );
    }

    // Filter by year established
    if ($request->filled('year')) {
        $yr = $request->year;
        $query->whereHas('businessProfile', function ($sq) use ($yr) {
            if ($yr === 'before_2000') {
                $sq->where('year_established', '<', 2000);
            } elseif ($yr === '2000') {
                $sq->whereBetween('year_established', [2000, 2009]);
            } elseif ($yr === '2010') {
                $sq->whereBetween('year_established', [2010, 2014]);
            } elseif ($yr === '2015') {
                $sq->whereBetween('year_established', [2015, 2019]);
            } elseif ($yr === '2020') {
                $sq->where('year_established', '>=', 2020);
            }
        });
    }

    // Sorting
    $sort = $request->get('sort', 'recent');
    match ($sort) {
        'oldest' => $query->oldest('users.seller_verified_at'),
        'name_asc' => $query->orderByRaw('COALESCE(ubp.company_name, users.name) ASC'),
        'name_desc' => $query->orderByRaw('COALESCE(ubp.company_name, users.name) DESC'),
        default => $query->latest('users.seller_verified_at'),
    };

    $suppliers = $query->distinct()->paginate(12)->withQueryString();

    // Dropdown options for filters
    $ownershipTypes = \App\Models\UserBusinessProfile::whereNotNull('ownership_type')
        ->distinct()->pluck('ownership_type')->sort()->values();

    $states = \App\Models\UserBusinessProfile::whereNotNull('state_name')
        ->distinct()->pluck('state_name')->sort()->values();

    return view('pages.suppliers', compact('suppliers', 'ownershipTypes', 'states'));

})->name('suppliers.all');

/*
|--------------------------------------------------------------------------
| Dedicated Seller Profile
|--------------------------------------------------------------------------
*/
Route::get('/seller/{id}', [SellerProfileController::class, 'show'])->name('seller.show');

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| Post Requirement
|--------------------------------------------------------------------------
*/
Route::get('/post-requirement', [\App\Http\Controllers\RequirementController::class, 'index'])->name('post-requirement');
Route::post('/post-requirement', [\App\Http\Controllers\RequirementController::class, 'store'])->name('post-requirement.store');

/*
|--------------------------------------------------------------------------
| Public Inquiry
|--------------------------------------------------------------------------
*/
Route::post('/products/{product}/inquiry', [InquiryController::class, 'store'])
    ->name('products.inquiry.store');

/*
|--------------------------------------------------------------------------
| Logged-in User Area
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('user')->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('user.dashboard');

    // Profile (Phase 1)
    Route::get('/profile', [UserProfileController::class, 'show'])->name('user.profile');
    Route::get('/business-profile', [UserProfileController::class, 'businessProfile'])->name('user.business-profile');
    Route::get('/bank-details', [UserProfileController::class, 'bankDetails'])->name('user.bank-details');
    Route::get('/manage-users', [UserProfileController::class, 'manageUsers'])->name('user.manage-users');
    Route::post('/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/personal', [UserProfileController::class, 'updatePersonal'])->name('user.profile.personal.update');
    Route::post('/business-profile', [UserProfileController::class, 'updateBusinessProfile'])->name('user.business-profile.update');
    Route::post('/bank-details', [UserProfileController::class, 'updateBankDetails'])->name('user.bank-details.update');

    // Inquiries (Phase 2)
    Route::get('/inquiries', [UserInquiriesController::class, 'index'])->name('user.inquiries');
    Route::get('/inquiries/{inquiry}', [UserInquiriesController::class, 'show'])
        ->name('user.inquiries.show');
    Route::post('/inquiries/{inquiry}/status', [UserInquiriesController::class, 'updateStatus'])
        ->name('user.inquiries.updateStatus');

    Route::get('/notifications', [UserNotificationController::class, 'index'])->name('user.notifications');
    Route::post('/notifications/read-all', [UserNotificationController::class, 'markAllRead'])
        ->name('user.notifications.read-all');
    Route::post('/notifications/{recipient}/read', [UserNotificationController::class, 'markRead'])
        ->name('user.notifications.read');

    Route::get('/support', [UserSupportTicketController::class, 'index'])->name('user.support.index');
    Route::post('/support', [UserSupportTicketController::class, 'store'])->name('user.support.store');

    // Products
    Route::get('/products', [ProductController::class, 'myProducts'])->name('user.products.index');
    Route::get('/products/export', [ProductController::class, 'exportMyProducts'])->name('user.products.export');
    Route::post('/products/import', [ProductController::class, 'importMyProducts'])->name('user.products.import');
    Route::get('/catalog', [ProductController::class, 'myCatalog'])->name('user.products.catalog');

    Route::get('/products/create', [ProductController::class, 'create'])->name('user.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('user.products.store');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('user.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('user.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('user.products.destroy');
    Route::post('/business-profile', [UserProfileController::class, 'updateBusinessProfile'])
        ->name('user.business-profile.update');
    Route::post('/upload', [UploadController::class, 'store'])->name('user.upload.store');
    Route::delete('/account', [UserProfileController::class, 'destroyAccount'])->name('user.account.destroy');
});
