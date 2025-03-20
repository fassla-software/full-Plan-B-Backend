<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use Illuminate\Http\{Request, JsonResponse, Response};
use App\Http\Requests\equipments\{UpdateEquipmentRequest, StoreEquipmentRequest};
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;

use Illuminate\Support\Facades\{Validator};
use Modules\Service\Entities\SubCategory;

class MyEquipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, string $categorySlug, string $sub_category_id): JsonResponse
    {
        $validator = Validator::make(
            compact('categorySlug', 'sub_category_id'),
            [
                'categorySlug'  => ['required', new Enum(MachineType::class)],
                'sub_category_id' => 'required|integer|exists:sub_categories,id',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $equipment = getEquipmentModelFromType($categorySlug);
        $locale = $request->header('Accept-Language', 'en');

        $sub_category = SubCategory::findOrFail($sub_category_id);

        $myEquipments = $equipment::query()
            ->with(['subCategory', 'user:id,first_name,last_name'])
            ->where('sub_category_id', $sub_category_id)
            ->where('user_id', $user->id)
            ->paginate(12)
            ->withQueryString();

        $myEquipments->getCollection()->transform(function ($equipment) {
            $decodedImages = json_decode($equipment->additional_equipment_images);

            $equipment->data_certificate_image = $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null;
            $equipment->driver_license_front_image =  $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null;
            $equipment->driver_license_back_image =  $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null;
            $equipment->tractor_license_front_image =  $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null;
            $equipment->tractor_license_back_image =  $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null;
            $equipment->flatbed_license_front_image =  $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null;
            $equipment->flatbed_license_back_image =  $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null;
            $equipment->additional_equipment_images = $equipment->additional_equipment_images
                ? array_map(fn($image) =>  $image ? asset('storage/assets/uploads/sub-category-images/' . $image) : null, $decodedImages ?? [])
                : [];
            return $equipment;
        });

        $eqName = $sub_category->getTranslatedName($locale);
        $eqImage = $this->getFullImageUrl($sub_category->image);

        return response()->json(
            [
                'success' => 'success',
                'data' => [
                    'name' => $eqName,
                    'image' => $eqImage,
                    'myEquipments' => $myEquipments,
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request, string $categorySlug): JsonResponse
    {
        $validator = Validator::make([
            'categorySlug' => $categorySlug,
        ], [
            'categorySlug' => ['required', new Enum(MachineType::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $equipmentModel = getEquipmentModelFromType($categorySlug);
        $validatedData = $request->validated();
        $validatedData['user_id'] = $user->id;

        $equipment = $equipmentModel::create($validatedData);
        $decodedImages = json_decode($equipment->additional_equipment_images);

        return response()->json([
            'message' => 'Equipment created successfully',
            'equipment' => array_merge($equipment->toArray(), [
                'data_certificate_image' => $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null,
                'driver_license_front_image' => $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null,
                'driver_license_back_image' => $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null,
                'tractor_license_front_image' => $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null,
                'tractor_license_back_image' => $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null,
                'flatbed_license_front_image' => $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null,
                'flatbed_license_back_image' => $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null,
                'additional_equipment_images' => array_map(
                    fn($image) => $image ? asset('storage/assets/uploads/sub-category-images/' . $image) : null,
                    $decodedImages ?? []
                ),
            ]),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $categorySlug, string $id): JsonResponse
    {
        $validator = Validator::make(
            compact('categorySlug'),
            [
                'categorySlug'  => ['required', new Enum(MachineType::class)],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $equipmentModel = getEquipmentModelFromType($categorySlug);

        $equipment = $equipmentModel::find($id);
        if (!$equipment) return response()->json(['message' => 'Equipment not found'], Response::HTTP_NOT_FOUND);

        $decodedImages = json_decode($equipment->additional_equipment_images);

        return response()->json([
            'equipment' => array_merge($equipment->toArray(), [
                'data_certificate_image' => $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null,
                'driver_license_front_image' => $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null,
                'driver_license_back_image' => $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null,
                'tractor_license_front_image' => $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null,
                'tractor_license_back_image' => $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null,
                'flatbed_license_front_image' => $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null,
                'flatbed_license_back_image' => $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null,
                'additional_equipment_images' => $equipment->additional_equipment_images
                    ? array_map(fn($image) => $image ? asset('storage/assets/uploads/sub-category-images/' . $image) : null, $decodedImages ?? [])
                    : [],
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, string $categorySlug, string $id): JsonResponse
    {
        $validator = Validator::make([
            'categorySlug' => $categorySlug,
        ], [
            'categorySlug' => ['required', new Enum(MachineType::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $equipmentModel = getEquipmentModelFromType($categorySlug);
        $equipment = $equipmentModel::findOrFail($id);
        $validatedData = $request->validated();

        $equipment->update($validatedData);

        $decodedImages = json_decode($equipment->additional_equipment_images);

        return response()->json([
            'message' => 'Equipment updated successfully',
            'equipment' => array_merge($equipment->toArray(), [
                'data_certificate_image' => $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null,
                'driver_license_front_image' => $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null,
                'driver_license_back_image' => $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null,
                'tractor_license_front_image' => $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null,
                'tractor_license_back_image' => $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null,
                'flatbed_license_front_image' => $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null,
                'flatbed_license_back_image' => $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null,
                'additional_equipment_images' => array_map(
                    fn($image) => $image ? asset('storage/assets/uploads/sub-category-images/' . $image) : null,
                    $decodedImages ?? []
                ),
            ]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $categorySlug, string $id)
    {
        $validator = Validator::make([
            'categorySlug' => $categorySlug,
        ], [
            'categorySlug' => ['required', new Enum(MachineType::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid request parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        $equipmentModel = getEquipmentModelFromType($categorySlug);

        $equipment = $equipmentModel::find($id);
        if (!$equipment) return response()->json(['message' => 'Equipment not found'], Response::HTTP_NOT_FOUND);

        $equipment->delete();

        return response()->json(
            [
                'message' => 'Equipment deleted successfully',
            ]
        );
    }

    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null;
        }

        $imageDetails = get_attachment_image_by_id($imageId);
        return $imageDetails['img_url'] ?? null;
    }
}
