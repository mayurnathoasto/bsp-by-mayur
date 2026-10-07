<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Services\Notifications\MarketplaceNotificationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminProductController extends Controller
{
    public function __construct(
        protected MarketplaceNotificationService $notifications
    ) {
    }

    public function index(Request $request)
    {
        $status = trim((string) $request->query('status', ''));
        $search = trim((string) $request->query('search', ''));
        $country = strtoupper(trim((string) $request->query('country', '')));

        $query = Product::query()
            ->with([
                'category:id,name',
                'subcategory:id,name',
                'user:id,name,email,phone,seller_status',
                'user.businessProfile:id,user_id,company_name,city_name,state_name,country_code',
            ])
            ->latest('id');

        if (in_array($status, [Product::STATUS_PENDING, Product::STATUS_LIVE], true)) {
            $query->where('status', $status);
        }

        if ($country !== '' && array_key_exists($country, config('countries.supported', []))) {
            $query->where('country_code', $country);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.businessProfile', function ($bq) use ($search) {
                        $bq->where('company_name', 'like', "%{$search}%")
                           ->orWhere('city_name', 'like', "%{$search}%")
                           ->orWhere('state_name', 'like', "%{$search}%");
                    });
            });
        }

        $products = $query->paginate(20);

        return response()->json([
            ...$products->toArray(),
            'country_options' => collect(config('countries.supported', []))
                ->map(fn (array $config, string $code) => [
                    'code' => $code,
                    'name' => $config['name'] ?? $code,
                ])
                ->sortBy('name')
                ->values()
                ->all(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $status = trim((string) $request->query('status', ''));
        $search = trim((string) $request->query('search', ''));
        $country = strtoupper(trim((string) $request->query('country', '')));

        $query = Product::query()
            ->with([
                'category:id,name',
                'subcategory:id,name',
                'user:id,name,email,phone',
                'user.businessProfile:id,user_id,company_name,city_name,state_name,country_code',
            ])
            ->latest('id');

        if (in_array($status, [Product::STATUS_PENDING, Product::STATUS_LIVE], true)) {
            $query->where('status', $status);
        }

        if ($country !== '' && array_key_exists($country, config('countries.supported', []))) {
            $query->where('country_code', $country);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user.businessProfile', function ($profileQuery) use ($search) {
                        $profileQuery->where('company_name', 'like', "%{$search}%")
                            ->orWhere('city_name', 'like', "%{$search}%")
                            ->orWhere('state_name', 'like', "%{$search}%");
                    });
            });
        }

        $rows = $query->get();
        $stamp = now()->format('Ymd_His');
        $fileName = 'products_export_' . $stamp . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Product ID',
                'Status',
                'Product Name',
                'Category',
                'Subcategory',
                'Seller Name',
                'Seller Email',
                'Seller Phone',
                'Company Name',
                'Country',
                'City',
                'State',
                'Currency',
                'Price',
                'MOQ',
                'Created At',
            ]);

            foreach ($rows as $product) {
                $profile = $product->user?->businessProfile;

                fputcsv($handle, [
                    $product->id,
                    $product->status,
                    $product->name,
                    $product->category?->name,
                    $product->subcategory?->name,
                    $product->user?->name,
                    $product->user?->email,
                    $product->user?->phone,
                    $profile?->company_name,
                    $product->country_code ?: $profile?->country_code,
                    $profile?->city_name,
                    $profile?->state_name,
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

    public function show($id)
    {
        $product = Product::with([
            'category:id,name',
            'subcategory:id,name',
            'specifications',
            'user:id,name,email,phone,seller_status',
            'user.businessProfile',
        ])->findOrFail($id);

        return response()->json($product);
    }

    public function approve($id)
    {
        $product = Product::findOrFail($id);

        $product->status = Product::STATUS_LIVE;
        $product->save();

        $this->notifications->notifyProductApproved($product);

        return response()->json([
            'message' => 'Product approved successfully',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000'
        ]);

        $product = Product::findOrFail($id);

        $product->status = 'rejected';
        $product->save();

        // Send a custom in-app notification to the seller with the reason
        $product->loadMissing('user');
        $this->notifications->createForUsers([$product->user], [
            'type' => 'product_rejected',
            'title' => 'Product rejected',
            'message' => sprintf(
                '"%s" has been rejected by admin. Reason: %s',
                $product->name,
                $request->reason ?? 'Not specified'
            ),
            'audience' => 'selected_users',
            'action_url' => route('user.products.index', ['status' => Product::STATUS_DRAFT]),
            'meta' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'rejection_reason' => $request->reason,
            ],
        ]);

        return response()->json([
            'message' => 'Product rejected successfully',
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete associated specifications first
        $product->specifications()->delete();
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return response()->json(['message' => 'Unable to read the uploaded file.'], 400);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return response()->json(['message' => 'The uploaded file is empty.'], 400);
        }

        $header = array_map(fn($col) => strtolower(trim((string) $col)), $header);

        $nameIdx = array_search('product name', $header) !== false ? array_search('product name', $header) : array_search('name', $header);
        $categoryIdx = array_search('category', $header) !== false ? array_search('category', $header) : array_search('category name', $header);
        $subcategoryIdx = array_search('subcategory', $header) !== false ? array_search('subcategory', $header) : array_search('subcategory name', $header);
        $priceIdx = array_search('price', $header);
        $moqIdx = array_search('moq', $header) !== false ? array_search('moq', $header) : array_search('min order quantity', $header);
        $descriptionIdx = array_search('description', $header);
        $sellerEmailIdx = array_search('seller email', $header) !== false ? array_search('seller email', $header) : array_search('email', $header);

        if ($nameIdx === false || $categoryIdx === false || $priceIdx === false || $descriptionIdx === false) {
            fclose($handle);
            return response()->json(['message' => 'Invalid CSV format. Required columns: Product Name, Category, Price, Description.'], 422);
        }

        $imported = 0;
        $sessionCountry = strtoupper(session('country', config('countries.default_country', 'IN')));
        $supportedCountries = config('countries.supported', []);

        while (($row = fgetcsv($handle)) !== false) {
            $name = trim($row[$nameIdx] ?? '');
            $categoryName = trim($row[$categoryIdx] ?? '');
            $subcategoryName = trim($row[$subcategoryIdx] ?? '');
            $price = floatval(trim($row[$priceIdx] ?? 0));
            $moq = intval(trim($row[$moqIdx] ?? 1));
            $description = trim($row[$descriptionIdx] ?? '');
            $sellerEmail = $sellerEmailIdx !== false ? trim($row[$sellerEmailIdx] ?? '') : '';

            if ($name === '' || $categoryName === '') {
                continue;
            }

            $seller = null;
            if ($sellerEmail !== '') {
                $seller = \App\Models\User::where('email', $sellerEmail)->first();
            }

            if (!$seller) {
                $seller = \App\Models\User::where('seller_status', \App\Models\User::SELLER_STATUS_APPROVED)->first();
            }

            if (!$seller) {
                continue;
            }

            $resolvedCountry = strtoupper($seller->businessProfile->country_code ?? $seller->country_code ?? $sessionCountry);
            $resolvedCurrency = strtoupper($supportedCountries[$resolvedCountry]['currency'] ?? config('countries.default_currency', 'INR'));

            $category = \App\Models\Category::firstOrCreate(
                ['name' => $categoryName],
                ['is_active' => true, 'sort_order' => 0]
            );

            $subcategoryId = null;
            if ($subcategoryName !== '') {
                $sub = \App\Models\Subcategory::firstOrCreate(
                    ['category_id' => $category->id, 'name' => $subcategoryName],
                    ['is_active' => true, 'sort_order' => 0]
                );
                $subcategoryId = $sub->id;
            }

            Product::create([
                'user_id' => $seller->id,
                'category_id' => $category->id,
                'subcategory_id' => $subcategoryId,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'currency' => $resolvedCurrency,
                'country_code' => $resolvedCountry,
                'min_order_quantity' => $moq > 0 ? $moq : 1,
                'images' => [],
                'status' => Product::STATUS_LIVE,
            ]);

            $imported++;
        }

        fclose($handle);

        return response()->json([
            'message' => "Successfully imported {$imported} products.",
        ]);
    }
}
