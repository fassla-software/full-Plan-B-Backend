<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;

class SiteServiceCar extends Model
{
    use HasFactory;

    protected $table = 'site_service_cars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size',
        'model',
        'year_of_manufacture',
        'moves_on',
        'current_equipment_location',
        'data_certificate_image',
        'driver_license_front_image',
        'driver_license_back_image',
        'additional_equipment_images',
        'special_rental_conditions',
        'blade_width',
        'blade_width_near_digging_arm',
        'engine_power',
        'milling_blade_width',
        'sprinkler_system_type',
        'tank_capacity',
        'panda_width',
        'has_bitumen_temp_gauge',
        'has_bitumen_level_gauge',
        'paving_range',
        'max_equipment_load',
        'boom_length',
        'load_at_max_boom_height',
        'load_at_max_horizontal_boom_extension',
        'max_lifting_point',
        'attachments',
        'has_tank_discharge_pump',
        'has_band_sprinkler_bar',
        'has_discharge_pump_with_liters_meter',
        'category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_bitumen_temp_gauge' => 'boolean',
        'has_bitumen_level_gauge' => 'boolean',
        'has_tank_discharge_pump' => 'boolean',
        'has_band_sprinkler_bar' => 'boolean',
        'has_discharge_pump_with_liters_meter' => 'boolean',
    ];

    /**
     * Get the category that owns the heavy equipment.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
