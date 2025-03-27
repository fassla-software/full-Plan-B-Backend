<?php

namespace App\Models;

use App\Models\User;
use Modules\Service\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScaffoldingAndMetalFormworkRentalJob extends Model
{
    use HasFactory;

    protected $table = 'scaffolding_rental_jobs';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($scaffolding) {
            Request::create([
                'user_id' => $scaffolding->user_id,
                'requestable_type' => ScaffoldingAndMetalFormworkRentalJob::class, // Polymorphic type
                'requestable_id' => $scaffolding->id,
            ]);
        });
    }

    protected $fillable = [
        'name',
        'sub_category_id',
        'user_id',
        'category_id',
        'lat',
        'long',
        'hour',
        'day',
        'month',
        'height_of_work_area_on_the_wall',
        'display_workspace_on_the_wall',
        'height_of_beginning_of_work_area_the_wall_from_floor',
        'scaffolding_base_mounting_floor_pictures',
        'work_wall_pictures',
        'work_site_location',
        'search_radius',
        'max_arrival_date',
        'max_offer_deadline',
        'additional_requirements',
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
