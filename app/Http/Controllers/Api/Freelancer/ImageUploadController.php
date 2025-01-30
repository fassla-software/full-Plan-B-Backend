<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ImageUploadController extends Controller
{
    public function handleEquipmentImages(Request $request)
    {
        return $request;
        // Validate that images are present and are of type image
        $request->validate([
            'images' => 'required|array', // Ensure it's an array
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Folder path
        $uploadFolder = 'public/assets/uploads/sub-category-images/';
        $imageNames = [];

        // Check if images exist in the request
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Generate a unique name
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store the image
                $image->move(storage_path('app/' . $uploadFolder), $imageName);

                // Get the URL
                //$imageNames[] = asset('storage/' . $uploadFolder . $imageName);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageNames,
        ]);
    }
}