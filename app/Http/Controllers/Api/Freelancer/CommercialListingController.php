<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\CommercialListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommercialListingController extends Controller
{
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $logoPath = $request->file('logo')->store('commercial-listings', 'public');
            
            return response()->json([
                'status' => true,
                'message' => 'Image uploaded successfully',
                'data' => [
                    'logo' => $logoPath
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while uploading the image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'logo' => 'required|string',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $listing = CommercialListing::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Commercial listing created successfully',
                'data' => $listing
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the listing',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
