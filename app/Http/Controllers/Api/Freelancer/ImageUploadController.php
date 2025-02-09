<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function handleEquipmentImages(Request $request)
    {
        // Validate images
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Storage folder (Don't include 'public/' prefix)
        $uploadFolder = 'assets/uploads/sub-category-images/';
        $imageNames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store image in storage/app/public (without 'public/' in the path)
                $image->storeAs('public/' . $uploadFolder, $imageName, 'public');

                // Generate URL for accessing the image
                $imageNames[] = asset('storage/' . $uploadFolder . $imageName);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageNames,
        ]);
    }
}
