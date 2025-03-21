<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class VehicleRental extends Model
{
    use HasFactory;

    protected $table = 'vehicle_rents';

    protected $fillable = [
        'sub_category_id',
        'user_id',
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
        'lat',
        'long',
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

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
