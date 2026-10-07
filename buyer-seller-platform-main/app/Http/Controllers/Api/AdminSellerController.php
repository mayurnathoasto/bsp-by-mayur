<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Services\Notifications\MarketplaceNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSellerController extends Controller
{
    public function __construct(
        protected MarketplaceNotificationService $notifications
    ) {
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('search', ''));

        $query = User::query()
            ->where('seller_status', '!=', User::SELLER_STATUS_NONE)
            ->with([
                'businessProfile:id,user_id,company_name,company_phone,address_line,city_name,state_name,country_code,ownership_type,year_established,employee_count,annual_turnover_currency,annual_turnover_amount,annual_turnover_unit,logo',
            ])
            ->withCount('products');

        if ($status) {
            $query->where('seller_status', $status);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('company_phone', 'like', "%{$search}%")
                    ->orWhere('company_address', 'like', "%{$search}%")
                    ->orWhereHas('businessProfile', function ($businessQuery) use ($search) {
                        $businessQuery->where('company_name', 'like', "%{$search}%")
                            ->orWhere('company_phone', 'like', "%{$search}%")
                            ->orWhere('city_name', 'like', "%{$search}%")
                            ->orWhere('state_name', 'like', "%{$search}%")
                            ->orWhere('country_code', 'like', "%{$search}%");
                    });
            });
        }

        $sellers = $query->latest('seller_submitted_at')->paginate(20);

        return response()->json($sellers);
    }

    public function show($id)
    {
        $seller = User::query()
            ->with([
                'city:id,name,state,country_code',
                'businessProfile',
                'bankDetail',
                'products' => function ($query) {
                    $query->with(['category:id,name', 'subcategory:id,name'])
                        ->latest('id');
                },
            ])
            ->withCount([
                'products',
                'products as live_products_count' => fn ($query) => $query->where('status', \App\Models\Product::STATUS_LIVE),
                'products as pending_products_count' => fn ($query) => $query->where('status', \App\Models\Product::STATUS_PENDING),
            ])
            ->findOrFail($id);

        return response()->json([
            'id' => $seller->id,
            'name' => $seller->name,
            'email' => $seller->email,
            'phone' => $seller->phone,
            'designation' => $seller->designation,
            'country_code' => $seller->country_code,
            'pincode' => $seller->pincode,
            'login_identifier' => $seller->login_identifier,
            'profile_photo_path' => $seller->profile_photo_path,
            'seller_status' => $seller->seller_status,
            'seller_submitted_at' => $seller->seller_submitted_at,
            'seller_verified_at' => $seller->seller_verified_at,
            'seller_rejection_reason' => $seller->seller_rejection_reason,
            'company_name' => $seller->company_name,
            'company_phone' => $seller->company_phone,
            'company_address' => $seller->company_address,
            'tax_id_type' => $seller->tax_id_type,
            'tax_id_number' => $seller->tax_id_number,
            'alternate_email' => $seller->alternate_email,
            'office_contact' => $seller->office_contact,
            'alternate_mobile' => $seller->alternate_mobile,
            'whatsapp_number' => $seller->whatsapp_number,
            'products_count' => $seller->products_count,
            'live_products_count' => $seller->live_products_count,
            'pending_products_count' => $seller->pending_products_count,
            'city' => $seller->city,
            'business_profile' => $seller->businessProfile,
            'bank_detail' => $seller->bankDetail,
            'products' => $seller->products,
        ]);
    }

    public function approve($id)
    {
        $seller = User::findOrFail($id);

        $seller->seller_status = User::SELLER_STATUS_APPROVED;
        $seller->seller_verified_at = now();
        $seller->seller_rejection_reason = null;
        $seller->save();

        $this->notifications->notifySellerApproved($seller);

        return response()->json(['message' => 'Seller approved successfully']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000'
        ]);

        $seller = User::findOrFail($id);

        $seller->seller_status = User::SELLER_STATUS_REJECTED;
        $seller->seller_verified_at = null;
        $seller->seller_rejection_reason = $request->reason;
        $seller->save();

        return response()->json(['message' => 'Seller rejected successfully']);
    }

    public function update(Request $request, $id)
    {
        $seller = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $seller->id,
            'phone' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'alternate_email' => 'nullable|email|max:255',
            'office_contact' => 'nullable|string|max:255',
            'alternate_mobile' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_address' => 'nullable|string|max:500',
            'tax_id_type' => 'nullable|string|max:255',
            'tax_id_number' => 'nullable|string|max:255',
            'seller_status' => 'required|string|in:none,pending,approved,rejected',
            'seller_rejection_reason' => 'nullable|string|max:1000',
        ]);

        // Handle verification status timestamps
        if ($data['seller_status'] === User::SELLER_STATUS_APPROVED && $seller->seller_status !== User::SELLER_STATUS_APPROVED) {
            $data['seller_verified_at'] = now();
            $data['seller_rejection_reason'] = null;
        } elseif ($data['seller_status'] === User::SELLER_STATUS_REJECTED) {
            $data['seller_verified_at'] = null;
        }

        $seller->update($data);

        // Also update the business profile if it exists to keep them in sync
        if ($seller->businessProfile) {
            $seller->businessProfile->update([
                'company_name' => $data['company_name'] ?? $seller->businessProfile->company_name,
                'company_phone' => $data['company_phone'] ?? $seller->businessProfile->company_phone,
                'address_line' => $data['company_address'] ?? $seller->businessProfile->address_line,
                'formatted_address' => $data['company_address'] ?? $seller->businessProfile->formatted_address,
            ]);
        }

        // Notify seller if status changed to approved
        if ($data['seller_status'] === User::SELLER_STATUS_APPROVED && $seller->wasChanged('seller_status')) {
            $this->notifications->notifySellerApproved($seller);
        }

        return response()->json([
            'message' => 'Seller details updated successfully',
            'seller' => $seller
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

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

            return response()->json(['message' => 'Account and all associated data permanently deleted.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete account.', 'error' => $e->getMessage()], 500);
        }
    }
}
