<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;

class VehicleRentalJob extends Model
{
    use HasFactory;
    protected $table = 'vehicle_rent_jobs';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($vehicleRentJob) {
            Request::create([
                'user_id' => $vehicleRentJob->user_id,
                'requestable_type' => VehicleRentalJob::class, // Polymorphic type
                'requestable_id' => $vehicleRentJob->id,
            ]);
        });
    }

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'required_work_location',
        'required_load_capacity',
        'required_rental_duration',
        'search_radius',
        'latest_availability_time',
        'offer_deadline',
        'work_description',
        'safety_compliance',
        'environmental_compliance',
        'night_lighting',
        'vehicle_type',
        'lat',
        'long',
    ];

    public function request()
    {
        return $this->morphOne(Request::class, 'requestable');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
}
