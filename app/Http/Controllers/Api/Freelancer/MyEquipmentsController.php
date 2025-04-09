<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;
use App\Models\generatorRentalLocation;
use App\Models\ScaffoldingRentalLocation;
use Modules\Service\Entities\SubCategory;
use Illuminate\Support\Facades\{Validator};
use Illuminate\Http\{Request, JsonResponse, Response};
use App\Http\Requests\CategoryRequest\CraneRentRequest;
use App\Http\Requests\CategoryRequest\GeneratorRequest;
use App\Http\Requests\CategoryRequest\ScaffoldingRequest;
use App\Http\Requests\CategoryRequest\VehicleRentRequest;
use App\Http\Requests\equipments\{StoreEquipmentRequest};
use App\Http\Requests\CategoryRequest\HeavyEquipmentRequest;

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

        $equipmentQuery = $equipment::query()
            ->with([
                'subCategory',
                'user:id,first_name,last_name',
            ]);

        $hasLocations = in_array($equipment, [
            \App\Models\GeneratorRental::class,
            \App\Models\ScaffoldingAndMetalFormworkRental::class,
        ]);

        if ($hasLocations) {
            $equipmentQuery->with('locations');
        }

        $myEquipments = $equipmentQuery
            ->where('sub_category_id', $sub_category_id)
            ->where('user_id', $user->id)
            ->paginate(12)
            ->withQueryString();

        $myEquipments->getCollection()->transform(function ($equipment) use ($hasLocations) {

            $equipment->data_certificate_image = $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null;
            $equipment->driver_license_front_image =  $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null;
            $equipment->driver_license_back_image =  $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null;
            $equipment->tractor_license_front_image =  $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null;
            $equipment->tractor_license_back_image =  $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null;
            $equipment->flatbed_license_front_image =  $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null;
            $equipment->flatbed_license_back_image =  $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null;
            $equipment->additional_equipment_images = collect(json_decode($equipment->additional_equipment_images, true) ?? [])
                ->map(fn($img) => asset('storage/assets/uploads/sub-category-images/' . $img))
                ->filter()
                ->values();

            if ($hasLocations && $equipment->locations) {
                $equipment->lat = $equipment->locations->pluck('lat')->toArray();
                $equipment->long = $equipment->locations->pluck('long')->toArray();
                $equipment->current_equipment_location = $equipment->locations->pluck('current_equipment_location')->toArray();
            }

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
                'additional_equipment_images' => collect(json_decode($equipment->additional_equipment_images, true) ?? [])
                    ->map(fn($img) => asset('storage/assets/uploads/sub-category-images/' . $img))
                    ->filter()
                    ->values(),
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

        $equipmentQuery = $equipmentModel::query();

        $hasLocations = in_array($equipmentModel, [
            \App\Models\GeneratorRental::class,
            \App\Models\ScaffoldingAndMetalFormworkRental::class,
        ]);

        if ($hasLocations) {
            $equipmentQuery->with('locations');
        }

        $equipment = $equipmentQuery->find($id);

        if ($hasLocations && $equipment->locations) {
            $equipment->lat = $equipment->locations->pluck('lat')->toArray();
            $equipment->long = $equipment->locations->pluck('long')->toArray();
            $equipment->current_equipment_location = $equipment->locations->pluck('current_equipment_location')->toArray();
        }

        if (!$equipment) return response()->json(['message' => 'Equipment not found'], Response::HTTP_NOT_FOUND);

        return response()->json([
            'equipment' => array_merge($equipment->toArray(), [
                'data_certificate_image' => $equipment->data_certificate_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->data_certificate_image) : null,
                'driver_license_front_image' => $equipment->driver_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_front_image) : null,
                'driver_license_back_image' => $equipment->driver_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->driver_license_back_image) : null,
                'tractor_license_front_image' => $equipment->tractor_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_front_image) : null,
                'tractor_license_back_image' => $equipment->tractor_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->tractor_license_back_image) : null,
                'flatbed_license_front_image' => $equipment->flatbed_license_front_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_front_image) : null,
                'flatbed_license_back_image' => $equipment->flatbed_license_back_image ? asset('storage/assets/uploads/sub-category-images/' . $equipment->flatbed_license_back_image) : null,
                'additional_equipment_images' => collect(json_decode($equipment->additional_equipment_images, true) ?? [])
                    ->map(fn($img) => asset('storage/assets/uploads/sub-category-images/' . $img))
                    ->filter()
                    ->values(),
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $categorySlug, string $id): JsonResponse
    {
        $requests = [
            MachineType::heavyEquipment->value => HeavyEquipmentRequest::class,
            MachineType::vehicleRental->value => VehicleRentRequest::class,
            MachineType::craneRental->value => CraneRentRequest::class,
            MachineType::generatorRental->value => GeneratorRequest::class,
            MachineType::scaffoldingToolsRental->value => ScaffoldingRequest::class,
            // add more requests
        ];

        if (!isset($requests[$categorySlug])) {
            return response()->json(['error' => 'Sub-category not found'], 404);
        }

        $validatedData = app($requests[$categorySlug])->validated();

        $equipmentModel = getEquipmentModelFromType($categorySlug);
        $equipment = $equipmentModel::findOrFail($id);

        $equipment->update($validatedData);

        if ($equipmentModel == \App\Models\GeneratorRental::class) {
            $latitudes = json_decode($validatedData['lat'] ?? '[]', true);
            $longitudes = json_decode($validatedData['long'] ?? '[]', true);
            $locations = json_decode($validatedData['current_generator_location'] ?? '[]', true);

            foreach ($latitudes as $index => $lat) {
                $long = $longitudes[$index] ?? null;
                $location = $locations[$index] ?? null;

                if (is_null($lat) || is_null($long) || is_null($location)) {
                    continue;
                }

                generatorRentalLocation::create([
                    'lat' => $lat,
                    'long' => $long,
                    'current_generator_location' => $location,
                ]);
            }
        } elseif ($equipmentModel == \App\Models\ScaffoldingAndMetalFormworkRental::class) {
            $latitudes = json_decode($validatedData['lat'] ?? '[]', true);
            $longitudes = json_decode($validatedData['long'] ?? '[]', true);
            $locations = json_decode($validatedData['current_equipment_location'] ?? '[]', true);

            foreach ($latitudes as $index => $lat) {
                $long = $longitudes[$index] ?? null;
                $location = $locations[$index] ?? null;

                if (is_null($lat) || is_null($long) || is_null($location)) {
                    continue;
                }

                ScaffoldingRentalLocation::create([
                    'lat' => $lat,
                    'long' => $long,
                    'current_equipment_location' => $location,
                ]);
            }
        }

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
                'additional_equipment_images' => collect(json_decode($equipment->additional_equipment_images, true) ?? [])
                    ->map(fn($img) => asset('storage/assets/uploads/sub-category-images/' . $img))
                    ->filter()
                    ->values(),
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
