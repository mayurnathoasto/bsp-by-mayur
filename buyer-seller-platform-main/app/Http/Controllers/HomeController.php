<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $country = session('country', 'IN');
        $currency = session('currency', 'INR');

        // Homepage products: latest 12
        $products = Product::query()
            ->latest()
            ->take(12)
            ->get();

        return view('pages.home', compact('country', 'currency', 'products'));
    }
}
