<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function handleEquipmentImages(Request $request)
    {
        // Validate that images are present and are of type image
        $request->validate([
            'images' => 'required|array', // Ensure it's an array
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Folder path to store images
        $uploadFolder = 'public/assets/uploads/sub-category-images/';
        $imageNames = [];

        // Check if images exist in the request
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique name for each image
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image in the specified folder
                $image->storeAs($uploadFolder, $imageName); // Store in storage/app/public

                // Get the URL of the image
                $imageUrl = Storage::url($uploadFolder . $imageName);

                // Add the image URL to the array
                $imageNames[] = $imageUrl;
            }
        }

        // Return the response with image URLs
        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageNames,
        ]);
    }
}
