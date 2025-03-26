<?php

namespace App\Models;

use App\Models\User;
use Modules\Service\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratorRentalJop extends Model
{
    use HasFactory;

    protected $table = 'generator_rental_jobs';

    protected static function boot()
    {
        parent::boot();

        static::created(function ($generatorRentalJob) {
            Request::create([
                'user_id' => $generatorRentalJob->user_id,
                'requestable_type' => GeneratorRentalJop::class, // Polymorphic type
                'requestable_id' => $generatorRentalJob->id,
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
        'generator_power',
        'work_site_location',
        'hour',
        'day',
        'month',
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
