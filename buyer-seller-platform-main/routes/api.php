<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminCategoryController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileMarketplaceController;
use App\Http\Controllers\Api\MobileSellerController;
use App\Http\Controllers\Api\AdminStaffController;
use App\Http\Controllers\Api\AdminSellerController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Api\AdminInquiryController;
use App\Http\Controllers\Api\AdminNotificationController;
use App\Http\Controllers\Api\AdminRequirementController;
use App\Http\Controllers\Api\AdminProductController;
use App\Http\Controllers\Api\AdminSupportTicketController;

// Public admin login
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Protected admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'admin.token'])->group(function () {

    Route::get('/me', [AdminAuthController::class, 'me']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::put('/profile', [AdminAuthController::class, 'updateProfile']);

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])
        ->middleware('admin.permission:categories.manage');
    Route::post('/categories', [AdminCategoryController::class, 'store'])
        ->middleware('admin.permission:categories.manage');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])
        ->middleware('admin.permission:categories.manage');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])
        ->middleware('admin.permission:categories.manage');

    // Subcategories
    Route::post('/subcategories', [AdminCategoryController::class, 'storeSubcategory'])
        ->middleware('admin.permission:categories.manage');
    Route::put('/subcategories/{id}', [AdminCategoryController::class, 'updateSubcategory'])
        ->middleware('admin.permission:categories.manage');
    Route::delete('/subcategories/{id}', [AdminCategoryController::class, 'destroySubcategory'])
        ->middleware('admin.permission:categories.manage');

    // Sellers
    Route::get('/sellers', [AdminSellerController::class, 'index'])
        ->middleware('admin.permission:sellers.view');
    Route::get('/sellers/{id}', [AdminSellerController::class, 'show'])
        ->middleware('admin.permission:sellers.view');
    Route::post('/sellers/{id}/approve', [AdminSellerController::class, 'approve'])
        ->middleware('admin.permission:sellers.approve');
    Route::post('/sellers/{id}/reject', [AdminSellerController::class, 'reject'])
        ->middleware('admin.permission:sellers.reject');
    Route::delete('/sellers/{id}', [AdminSellerController::class, 'destroy'])
        ->middleware('admin.permission:sellers.delete');
    Route::put('/sellers/{id}', [AdminSellerController::class, 'update'])
        ->middleware('admin.permission:sellers.edit');

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])
        ->middleware('admin.permission:products.view');
    Route::get('/products/export', [AdminProductController::class, 'export'])
        ->middleware('admin.permission:products.view');
    Route::post('/products/import', [AdminProductController::class, 'import'])
        ->middleware('admin.permission:products.approve');
    Route::get('/products/{id}', [AdminProductController::class, 'show'])
        ->middleware('admin.permission:products.view');
    Route::post('/products/{id}/approve', [AdminProductController::class, 'approve'])
        ->middleware('admin.permission:products.approve');
    Route::post('/products/{id}/reject', [AdminProductController::class, 'reject'])
        ->middleware('admin.permission:products.approve');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])
        ->middleware('admin.permission:products.delete');

    // Product enquiries
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])
        ->middleware('admin.permission:inquiries.view');
    Route::get('/inquiries/{id}', [AdminInquiryController::class, 'show'])
        ->middleware('admin.permission:inquiries.view');
    Route::put('/inquiries/{id}/notes', [AdminInquiryController::class, 'updateNotes'])
        ->middleware('admin.permission:inquiries.view');
    Route::get('/inquiries/{id}/distribute-options', [AdminInquiryController::class, 'getDistributeOptions'])
        ->middleware('admin.permission:inquiries.view');
    Route::post('/inquiries/{id}/distribute', [AdminInquiryController::class, 'distribute'])
        ->middleware('admin.permission:inquiries.view');

    // Bulk requirements
    Route::get('/requirements', [AdminRequirementController::class, 'index'])
        ->middleware('admin.permission:requirements.view');
    Route::get('/requirements/{id}', [AdminRequirementController::class, 'show'])
        ->middleware('admin.permission:requirements.view');

    // Notifications
    Route::get('/notifications', [AdminNotificationController::class, 'index'])
        ->middleware('admin.permission:notifications.manage');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])
        ->middleware('admin.permission:notifications.manage');

    // Support tickets and reports
    Route::get('/support-cases', [AdminSupportTicketController::class, 'index'])
        ->middleware('admin.permission:support.view');
    Route::get('/support-cases/{supportTicket}', [AdminSupportTicketController::class, 'show'])
        ->middleware('admin.permission:support.view');
    Route::post('/support-cases/{supportTicket}/reply', [AdminSupportTicketController::class, 'reply'])
        ->middleware('admin.permission:support.reply');

    // Sellers with pending status
    Route::get('/buyers', function () {
        return response()->json(
            \App\Models\User::query()
                ->whereNull('seller_status')
                ->select('id', 'name', 'phone', 'email', 'created_at')
                ->latest('id')
                ->paginate(20)
        );
    })->middleware('admin.permission:buyers.view');

    Route::post('/upload', [UploadController::class, 'store'])
        ->middleware('admin.permission:categories.manage');

    Route::get('/staff', [AdminStaffController::class, 'index'])
        ->middleware('admin.permission:staff.manage');
    Route::post('/staff', [AdminStaffController::class, 'store'])
        ->middleware('admin.permission:staff.manage');
    Route::put('/staff/{staff}', [AdminStaffController::class, 'update'])
        ->middleware('admin.permission:staff.manage');
    Route::delete('/staff/{staff}', [AdminStaffController::class, 'destroy'])
        ->middleware('admin.permission:staff.manage');
});

Route::prefix('mobile')->group(function () {
    Route::get('/config', [MobileMarketplaceController::class, 'config']);
    Route::get('/home', [MobileMarketplaceController::class, 'home']);
    Route::get('/categories', [MobileMarketplaceController::class, 'categories']);
    Route::get('/categories/{category}', [MobileMarketplaceController::class, 'categoryDetail']);
    Route::get('/products', [MobileMarketplaceController::class, 'products']);
    Route::get('/products/{product}', [MobileMarketplaceController::class, 'showProduct']);
    Route::post('/products/{product}/inquiries', [MobileMarketplaceController::class, 'submitInquiry']);

    Route::post('/auth/request-otp', [MobileAuthController::class, 'requestOtp']);
    Route::post('/auth/verify-otp', [MobileAuthController::class, 'verifyOtp']);

    Route::middleware('user.token')->group(function () {
        Route::get('/me', [MobileAuthController::class, 'me']);
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::post('/upload', [UploadController::class, 'store']);

        Route::prefix('seller')->group(function () {
            Route::get('/dashboard', [MobileSellerController::class, 'dashboard']);

            Route::get('/profile/personal', [MobileSellerController::class, 'personalProfile']);
            Route::put('/profile/personal', [MobileSellerController::class, 'updatePersonal']);

            Route::get('/profile/business', [MobileSellerController::class, 'businessProfile']);
            Route::put('/profile/business', [MobileSellerController::class, 'updateBusinessProfile']);

            Route::get('/profile/bank', [MobileSellerController::class, 'bankDetails']);
            Route::put('/profile/bank', [MobileSellerController::class, 'updateBankDetails']);

            Route::get('/inquiries', [MobileSellerController::class, 'inquiries']);
            Route::get('/inquiries/{inquiry}', [MobileSellerController::class, 'showInquiry']);
            Route::post('/inquiries/{inquiry}/status', [MobileSellerController::class, 'updateInquiryStatus']);

            Route::get('/products/meta', [MobileSellerController::class, 'productFormMeta']);
            Route::get('/products', [MobileSellerController::class, 'products']);
            Route::post('/products', [MobileSellerController::class, 'storeProduct']);
            Route::get('/products/{product}', [MobileSellerController::class, 'showProduct']);
            Route::put('/products/{product}', [MobileSellerController::class, 'updateProduct']);
            Route::delete('/products/{product}', [MobileSellerController::class, 'destroyProduct']);
        });
    });
});
