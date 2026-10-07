<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserBusinessProfile;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $type = (string) $request->input('type', 'Products');
        $sessionCity = strtolower(trim((string) session('city_name', '')));
        $sessionCityCountry = strtoupper(trim((string) session('city_country_code', session('country', 'IN'))));
        $currentCountry = strtoupper(session('country', 'IN'));

        $products = collect();
        $suppliers = collect();

        if (strtolower($type) === 'suppliers') {
            $suppliersQuery = User::query()
                ->select('users.*')
                ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
                ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
                ->has('products')
                ->when($query !== '', function ($q) use ($query) {
                    $q->where(function ($sub) use ($query) {
                        $sub->where('users.name', 'like', "%{$query}%")
                            ->orWhere('ubp.company_name', 'like', "%{$query}%")
                            ->orWhere('ubp.city_name', 'like', "%{$query}%")
                            ->orWhere('ubp.state_name', 'like', "%{$query}%");
                    });
                });

            $suppliersQuery->orderByRaw(
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

            $suppliers = $suppliersQuery
                ->with(['businessProfile', 'city'])
                ->withCount('products')
                ->orderByDesc('users.created_at')
                ->distinct()
                ->paginate(15)
                ->withQueryString();

        } else {
            $productsQuery = Product::query()
                ->select('products.*')
                ->join('users', 'users.id', '=', 'products.user_id')
                ->leftJoin('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
                ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
                ->where('products.status', Product::STATUS_LIVE)
                ->when($query !== '', function ($q) use ($query) {
                    $q->where(function ($subQ) use ($query) {
                        $subQ->where('products.name', 'like', "%{$query}%")
                            ->orWhere('products.description', 'like', "%{$query}%");
                    });
                });

            $productsQuery->orderByRaw(
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

            $products = $productsQuery
                ->with(['category', 'subcategory', 'user.businessProfile'])
                ->orderByDesc('products.created_at')
                ->paginate(15)
                ->withQueryString();
        }

        return view('pages.search', [
            'query' => $query,
            'q' => $query,
            'type' => $type,
            'products' => $products,
            'suppliers' => $suppliers,
        ]);
    }

    public function suppliers(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $sort = (string) $request->get('sort', 'recent');
        $sessionCity = trim((string) session('city_name', ''));

        $suppliersQuery = User::query()
            ->select('users.*')
            ->join('user_business_profiles as ubp', 'ubp.user_id', '=', 'users.id')
            ->where('users.seller_status', User::SELLER_STATUS_APPROVED)
            ->with(['businessProfile', 'city'])
            ->withCount('products');

        if ($q !== '') {
            $suppliersQuery->where(function ($sub) use ($q) {
                $sub->where('users.name', 'like', "%{$q}%")
                    ->orWhere('ubp.company_name', 'like', "%{$q}%")
                    ->orWhere('ubp.city_name', 'like', "%{$q}%")
                    ->orWhere('ubp.state_name', 'like', "%{$q}%")
                    ->orWhere('ubp.business_type', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $suppliersQuery->where('ubp.ownership_type', $request->type);
        }

        if ($request->filled('state')) {
            $suppliersQuery->where('ubp.state_name', $request->state);
        }

        if ($request->filled('year')) {
            $yr = $request->year;
            $suppliersQuery->where(function ($sq) use ($yr) {
                if ($yr === 'before_2000') {
                    $sq->where('ubp.year_established', '<', 2000);
                } elseif ($yr === '2000') {
                    $sq->whereBetween('ubp.year_established', [2000, 2009]);
                } elseif ($yr === '2010') {
                    $sq->whereBetween('ubp.year_established', [2010, 2014]);
                } elseif ($yr === '2015') {
                    $sq->whereBetween('ubp.year_established', [2015, 2019]);
                } elseif ($yr === '2020') {
                    $sq->where('ubp.year_established', '>=', 2020);
                }
            });
        }

        if ($sessionCity !== '') {
            $suppliersQuery->orderByRaw(
                "CASE WHEN LOWER(COALESCE(ubp.city_name, '')) = ? THEN 0 ELSE 1 END",
                [mb_strtolower($sessionCity)]
            );
        }

        switch ($sort) {
            case 'oldest':
                $suppliersQuery->oldest('users.seller_verified_at');
                break;
            case 'name_asc':
                $suppliersQuery->orderByRaw('COALESCE(ubp.company_name, users.name) ASC');
                break;
            case 'name_desc':
                $suppliersQuery->orderByRaw('COALESCE(ubp.company_name, users.name) DESC');
                break;
            case 'recent':
            default:
                $suppliersQuery->latest('users.seller_verified_at');
                break;
        }

        $suppliers = $suppliersQuery
            ->distinct()
            ->paginate(12)
            ->withQueryString();

        $ownershipTypes = UserBusinessProfile::whereNotNull('ownership_type')
            ->distinct()
            ->pluck('ownership_type')
            ->sort()
            ->values();

        $states = UserBusinessProfile::whereNotNull('state_name')
            ->distinct()
            ->pluck('state_name')
            ->sort()
            ->values();

        return view('pages.suppliers', compact('suppliers', 'ownershipTypes', 'states'));
    }
}