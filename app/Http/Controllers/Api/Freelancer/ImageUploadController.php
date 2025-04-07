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

        // Define the absolute path for uploads
        $uploadFolder = 'sub-category-images';
        $imageNames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Store directly in the public assets directory
                $image->move(public_path('assets/uploads/sub-category-images'), $imageName);

                // Generate URL using the direct public path
                $imageNames[] = url('assets/uploads/sub-category-images/' . $imageName);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageNames,
        ]);
    }
}
