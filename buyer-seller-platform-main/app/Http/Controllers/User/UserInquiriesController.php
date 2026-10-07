<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;

class UserInquiriesController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $q = ProductInquiry::query()
            ->with(['product'])
            ->where('seller_user_id', $user->id)
            ->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        $inquiries = $q->paginate(15)->withQueryString();

        return view('user.inquiries', [
            'inquiries' => $inquiries,
        ]);
    }

    public function show(ProductInquiry $inquiry)
    {
        $user = auth()->user();

        // SECURITY: seller can only see their own inquiries
        if ($inquiry->seller_user_id !== $user->id) {
            abort(403, 'Unauthorized access');
        }

        $inquiry->load([
            'product',
            'buyer',
            'seller',
        ]);

        return view('user.inquiry_show', compact('inquiry'));
    }

    public function updateStatus(Request $request, \App\Models\ProductInquiry $inquiry)
    {
        $user = auth()->user();

        if ($inquiry->seller_user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $inquiry->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('user.inquiries.show', $inquiry)
            ->with('success', 'Inquiry status updated successfully.');
    }
}
