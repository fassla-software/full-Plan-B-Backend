<?php

namespace App\Models;

use App\Models\HeavyEquipmentJob;
use Modules\Service\Entities\Category;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\SubCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeavyEquipmentJob extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($heavyEquipmentJob) {
            Request::create([
                'user_id' => $heavyEquipmentJob->user_id,
                'requestable_type' => HeavyEquipmentJob::class, // Polymorphic type
                'requestable_id' => $heavyEquipmentJob->id,
            ]);
        });
    }

    protected $fillable = [
        'sub_category_id',
        'name',
        'user_id',
        'category_id',
        'work_site_location',
        'hour',
        'day',
        'month',
        'search_radius',
        'max_arrival_date',
        'max_offer_deadline',
        'size',
        'attachments',
        'flatbed_load_description',
        'flatbed_loading_location',
        'flatbed_destination_location',
        'asphalt_scraper_movement',
        'safety_compliant',
        'environmental_compliant',
        'has_night_lighting',
        'additional_requirements',
        'lat',
        'long',
    ];

    protected $casts = [
        'attachments' => 'array',
        'safety_compliant' => 'boolean',
        'environmental_compliant' => 'boolean',
        'has_night_lighting' => 'boolean',
    ];

    public function request()
    {
        return $this->morphOne(Request::class, 'requestable');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function heavy_equipment(): BelongsTo
    {
        return $this->belongsTo(HeavyEquipment::class, 'heavy_equipment_id');
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
