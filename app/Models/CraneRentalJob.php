<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Modules\Service\Entities\Category;

class CraneRentalJob extends Model
{
    use HasFactory;

    protected $table = 'crane_rent_jobs';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($craneRentJob) {
            Request::create([
                'user_id' => $craneRentJob->user_id,
                'requestable_type' => CraneRentalJob::class, // Polymorphic type
                'requestable_id' => $craneRentJob->id,
            ]);
        });
    }

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'worksite_location',
        'hour',
        'day',
        'month',
        'search_range_around_worksite',
        'max_arrival_date',
        'max_offer_deadline',
        'additional_requirements',
        'number_of_loading_points',
        'load_image',
        'load_weight',
        'load_location',
        'number_of_load_destinations',
        'unloading_location',
        'load_start_time',
        'search_range_around_loading_location',
        'offer_submission_deadline',
        'required_height',
        'required_load',
        'furniture_lifting_to_floor',
        'safety_compliant',
        'environmental_compliant',
        'has_night_lighting',
        'lat',
        'long',
        'search_radius',
        'isStopped',
        'isSeen',
    ];

    public function request()
    {
        return $this->morphOne(Request::class, 'requestable');
    }

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
