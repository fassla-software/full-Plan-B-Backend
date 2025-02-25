<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class CraneRental extends Model
{
    use HasFactory;

    protected $table = 'crane_rents';

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'model',
        'type',
        'boom_length',
        'truck_load_capacity',
        'load_at_max_arm_height',
        'load_at_max_arm_distance',
        'current_location',
        'additional_equipment_images',
        'vehicle_license_front',
        'vehicle_license_back',
        'driver_license_front',
        'driver_license_back',
        'custom_conditions',
        'load_data_documents',
        'installation_time',
        'base_area_required',
        'maximum_height',
        'maximum_load_capacity',
        'insurance_documents',
        'actual_load_at_max_distance',
        'operator_qualification_documents',
        'lat',
        'long',
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
