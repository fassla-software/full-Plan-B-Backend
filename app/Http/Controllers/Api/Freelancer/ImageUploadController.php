<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ImageUploadController extends Controller
{
    public function handleEquipmentImages(Request $request)
    {
        // Validate that images are present and are of type image
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Define the folder path within the storage/public directory
        $uploadFolder = 'sub-category-images/';
        $imageNames = [];

        // Check if images are sent in the request
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique name for the image
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image in the storage/app/public/sub-category-images folder
                $path = $image->storeAs('public/' . $uploadFolder, $imageName);

                // Get the publicly accessible URL of the stored image
                $imageNames[] = asset('storage/' . $uploadFolder . $imageName);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageNames,
        ]);
    }
}