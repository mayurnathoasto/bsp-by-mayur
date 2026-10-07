<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    // POST /products/{product}/inquiry  (public)
    public function store(Request $request, Product $product)
    {
        $user = $request->user(); // null if guest
        $country = session('country', 'IN');

        $data = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:1500'],

            // If guest, require at least one contact field.
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // If not logged in, require at least phone or email
        if (!$user) {
            $hasContact = !empty($data['phone']) || !empty($data['email']);
            if (!$hasContact) {
                return redirect()->to(route('products.show', $product) . '#inquiry-form')
                    ->withErrors(['phone' => 'Phone or Email is required for guest inquiry.'])
                    ->withInput();
            }
        }

        ProductInquiry::create([
            'product_id' => $product->id,
            'buyer_user_id' => $user?->id,
            'seller_user_id' => $product->user_id, // nullable ok for demo products
            'name' => $user ? $user->name : ($data['name'] ?? null),
            'email' => $user ? $user->email : ($data['email'] ?? null),
            'phone' => $user ? $user->phone : ($data['phone'] ?? null),
            'message' => $data['message'],
            'status' => 'new',
        ]);

        return redirect()->to(route('products.show', $product) . '#inquiry-form')
            ->with('success', 'Inquiry sent successfully.');
    }
}
