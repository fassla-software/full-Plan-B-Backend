<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommercialListing;
use Illuminate\Http\Request;

class CommercialListingController extends Controller
{
    public function index()
    {
        $listings = CommercialListing::latest()->paginate(10);
        return view('admin.commercial-listings.index', compact('listings'));
    }

    public function updateStatus(Request $request, CommercialListing $listing)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $listing->update(['status' => $request->status]);

        return back()->with('success', 'Status updated successfully');
    }
}
