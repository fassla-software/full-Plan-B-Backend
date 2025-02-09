<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;

class VehicleRent extends Model
{
    use HasFactory;

    protected $table = 'vehicle_rents';

    protected $fillable = [
        'name',
        'category_id',
        'vehicle_load',
        'model',
        'current_vehicle_location',
        'vehicle_license_front_image',
        'vehicle_license_back_image',
        'driver_license_front_image',
        'driver_license_back_image',
        'additional_vehicle_images',
        'additional_vehicle_images.*',
        'comment',
        'has_tank_discharge_pump',
        'has_band_sprinkler_bar',
        'has_discharge_pump_with_liters_meter',
    ];

    protected $casts = [
        'has_tank_discharge_pump' => 'boolean',
        'has_band_sprinkler_bar' => 'boolean',
        'has_discharge_pump_with_liters_meter' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
