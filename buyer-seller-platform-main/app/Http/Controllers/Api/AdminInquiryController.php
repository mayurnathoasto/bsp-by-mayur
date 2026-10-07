<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductInquiry;
use App\Models\User;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = trim((string) $request->get('status', ''));
        $country = strtoupper(trim((string) $request->get('country', '')));
        $city = trim((string) $request->get('city', ''));
        $seller = trim((string) $request->get('seller', ''));

        $query = ProductInquiry::query()
            ->with([
                'product:id,name,country_code',
                'buyer:id,name,email,phone',
                'seller:id,name,email,phone',
                'seller.businessProfile:user_id,company_name,city_name,state_name,country_code',
            ])
            ->latest('id');

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('message', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhereHas('product', function ($pq) use ($q) {
                        $pq->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('seller', function ($sq) use ($q) {
                        $sq->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhereHas('seller.businessProfile', function ($bpq) use ($q) {
                        $bpq->where('company_name', 'like', "%{$q}%")
                            ->orWhere('city_name', 'like', "%{$q}%")
                            ->orWhere('state_name', 'like', "%{$q}%");
                    });
            });
        }

        if ($country !== '') {
            $query->whereHas('seller.businessProfile', function ($bpq) use ($country) {
                $bpq->where('country_code', $country);
            });
        }

        if ($city !== '') {
            $query->whereHas('seller.businessProfile', function ($bpq) use ($city) {
                $bpq->where('city_name', 'like', "%{$city}%");
            });
        }

        if ($seller !== '') {
            $query->where(function ($sq) use ($seller) {
                $sq->whereHas('seller', function ($sellerQ) use ($seller) {
                    $sellerQ->where('name', 'like', "%{$seller}%")
                        ->orWhere('email', 'like', "%{$seller}%");
                })->orWhereHas('seller.businessProfile', function ($bpq) use ($seller) {
                    $bpq->where('company_name', 'like', "%{$seller}%");
                });
            });
        }

        $rows = $query->paginate(20);

        $rows->getCollection()->transform(function ($inq) {
            $sellerProfile = optional($inq->seller)->businessProfile;

            return [
                'id' => $inq->id,
                'status' => $inq->status,
                'message' => $inq->message,
                'created_at' => optional($inq->created_at)?->toISOString(),

                'buyer_name' => $inq->buyer?->name ?: $inq->name,
                'buyer_email' => $inq->buyer?->email ?: $inq->email,
                'buyer_phone' => $inq->buyer?->phone ?: $inq->phone,

                'product_id' => $inq->product?->id,
                'product_name' => $inq->product?->name,
                'product_country_code' => $inq->product?->country_code,

                'seller_id' => $inq->seller?->id,
                'seller_name' => $inq->seller?->name,
                'seller_email' => $inq->seller?->email,
                'seller_company_name' => $sellerProfile?->company_name,
                'seller_city_name' => $sellerProfile?->city_name,
                'seller_state_name' => $sellerProfile?->state_name,
                'seller_country_code' => $sellerProfile?->country_code,
            ];
        });

        return response()->json($rows);
    }

    public function show($id)
    {
        $inq = ProductInquiry::query()
            ->with([
                'product',
                'buyer',
                'seller',
                'seller.businessProfile',
            ])
            ->findOrFail($id);

        $sellerProfile = optional($inq->seller)->businessProfile;

        return response()->json([
            'id' => $inq->id,
            'status' => $inq->status,
            'admin_notes' => $inq->admin_notes,
            'message' => $inq->message,
            'created_at' => optional($inq->created_at)?->toISOString(),

            'buyer' => [
                'id' => $inq->buyer?->id,
                'name' => $inq->buyer?->name ?: $inq->name,
                'email' => $inq->buyer?->email ?: $inq->email,
                'phone' => $inq->buyer?->phone ?: $inq->phone,
            ],

            'product' => [
                'id' => $inq->product?->id,
                'name' => $inq->product?->name,
                'country_code' => $inq->product?->country_code,
            ],

            'seller' => [
                'id' => $inq->seller?->id,
                'name' => $inq->seller?->name,
                'email' => $inq->seller?->email,
                'phone' => $inq->seller?->phone,
                'company_name' => $sellerProfile?->company_name,
                'city_name' => $sellerProfile?->city_name,
                'state_name' => $sellerProfile?->state_name,
                'country_code' => $sellerProfile?->country_code,
            ],
        ]);
    }

    public function updateNotes(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:2000'
        ]);

        $inq = ProductInquiry::findOrFail($id);
        $inq->admin_notes = $request->notes;
        $inq->save();

        return response()->json([
            'message' => 'Enquiry notes updated successfully',
            'admin_notes' => $inq->admin_notes
        ]);
    }

    public function getDistributeOptions($id)
    {
        $inq = ProductInquiry::with('product')->findOrFail($id);

        $sellers = $this->distributionSellerQuery($inq)
            ->with('businessProfile:user_id,company_name,city_name,state_name,country_code')
            ->get();

        // If empty, fallback to all approved sellers (excluding original)
        if ($sellers->isEmpty()) {
            $sellers = User::query()
                ->where('seller_status', User::SELLER_STATUS_APPROVED)
                ->where('id', '!=', $inq->seller_user_id)
                ->with('businessProfile:user_id,company_name,city_name,state_name,country_code')
                ->get();
        }

        return response()->json($sellers->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'email' => $s->email,
            'company_name' => $s->businessProfile?->company_name,
            'city_name' => $s->businessProfile?->city_name,
            'state_name' => $s->businessProfile?->state_name,
            'country_code' => $s->businessProfile?->country_code,
        ]));
    }

    public function distribute(Request $request, $id)
    {
        $request->validate([
            'seller_ids' => 'required|array',
            'seller_ids.*' => 'exists:users,id',
        ]);

        $inq = ProductInquiry::with('product')->findOrFail($id);
        $allowedSellerIds = $this->distributionSellerQuery($inq)
            ->whereIn('id', $request->seller_ids)
            ->pluck('id')
            ->all();

        $distributedCount = 0;

        foreach ($allowedSellerIds as $sellerId) {
            // Check if already distributed to prevent duplicates
            $exists = ProductInquiry::where('product_id', $inq->product_id)
                ->where('buyer_user_id', $inq->buyer_user_id)
                ->where('seller_user_id', $sellerId)
                ->where('message', $inq->message)
                ->exists();

            if (!$exists) {
                ProductInquiry::create([
                    'product_id' => $inq->product_id,
                    'buyer_user_id' => $inq->buyer_user_id,
                    'seller_user_id' => $sellerId,
                    'name' => $inq->name,
                    'email' => $inq->email,
                    'phone' => $inq->phone,
                    'message' => $inq->message,
                    'status' => 'new',
                ]);
                $distributedCount++;
            }
        }

        return response()->json([
            'message' => "Enquiry distributed successfully to {$distributedCount} sellers.",
        ]);
    }

    private function distributionSellerQuery(ProductInquiry $inq)
    {
        $subcategoryId = $inq->product?->subcategory_id;
        $categoryId = $inq->product?->category_id;

        $query = User::query()
            ->where('seller_status', User::SELLER_STATUS_APPROVED)
            ->where('id', '!=', $inq->seller_user_id);

        $matchingQuery = clone $query;

        if ($subcategoryId || $categoryId) {
            $matchingQuery->whereHas('products', function ($pq) use ($subcategoryId, $categoryId) {
                $pq->where(function ($match) use ($subcategoryId, $categoryId) {
                    if ($subcategoryId) {
                        $match->orWhere('subcategory_id', $subcategoryId);
                    }

                    if ($categoryId) {
                        $match->orWhere('category_id', $categoryId);
                    }
                });
            });
        }

        return $matchingQuery->exists() ? $matchingQuery : $query;
    }
}
