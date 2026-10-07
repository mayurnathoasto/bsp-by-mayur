<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequirementController extends Controller
{
    /**
     * Show the "Post Requirement" page.
     */
    public function index()
    {
        return view('pages.post_requirement');
    }

    /**
     * Handle the form submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'financing' => 'nullable|string|in:yes,no',
            'gst_status' => 'nullable|string|in:yes,no',
        ]);

        \App\Models\Requirement::create([
            'user_id' => auth()->id(),
            'product_name' => $request->product_name,
            'financing' => $request->financing ?? 'no',
            'gst_status' => $request->gst_status,
            // Voice recording logic can be added here when file upload is implemented
            'voice_recording' => null,
        ]);

        return back()->with('success', 'Your requirement has been posted successfully!');
    }
}
