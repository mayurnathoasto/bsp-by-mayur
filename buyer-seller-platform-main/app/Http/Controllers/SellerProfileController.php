<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerProfileController extends Controller
{
    public function show($id)
    {
        // Find seller by id where seller_status = 'approved'
        $seller = User::where('id', $id)
            ->where('seller_status', 'approved')
            ->with(['businessProfile', 'city'])
            ->firstOrFail();

        // Get only seller's public visible (live) products
        $products = Product::publicVisible()
            ->where('user_id', $seller->id)
            ->with('category')
            ->latest()
            ->paginate(12);

        $title = ($seller->businessProfile->company_name ?? $seller->name) . ' - Supplier Profile | Buyer Seller Platform';

        return view('pages.seller_profile', compact('seller', 'products', 'title'));
    }
}