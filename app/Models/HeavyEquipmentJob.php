<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeavyEquipmentJob extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'attachments' => 'array',
        'safety_compliant' => 'boolean',
        'environmental_compliant' => 'boolean',
        'has_night_lighting' => 'boolean',
    ];
}
