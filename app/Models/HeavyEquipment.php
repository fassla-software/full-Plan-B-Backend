<?php

namespace App\Models;

use App\Models\HeavyEquipmentJob;
use Modules\Service\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeavyEquipment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'heavy_equipment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_category_id',
        'user_id',
        'category_id',
        'size',
        'name',
        'model',
        'year_of_manufacture',
        'current_equipment_location',
        'data_certificate_image',
        'driver_license_front_image',
        'driver_license_back_image',
        'additional_equipment_images',
        'special_rental_conditions',
        'blade_width_near_digging_arm',
        'add_bucket',
        'sprinkler_system_type',
        'tank_capacity',
        'panda_width',
        'has_bitumen_temp_gauge',
        'has_bitumen_level_gauge',
        'max_equipment_load',
        'boom_length',
        'load_at_max_boom_height',
        'load_at_max_horizontal_boom_extension',
        'tractor_license_front_image',
        'tractor_license_back_image',
        'engine_power',
        'blade_width',
        'blade_type',
        'flatbed_license_front_image',
        'flatbed_license_back_image',
        'moves_on',
        'scraper_width',
        'lat',
        'long',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_bitumen_temp_gauge' => 'boolean',
        'has_bitumen_level_gauge' => 'boolean',
        'additional_equipment_images' => 'array',
    ];

    /**
     * Get the category that owns the heavy equipment.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function equipment_jops()
    {
        return $this->hasMany(HeavyEquipmentJob::class, 'equipment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
