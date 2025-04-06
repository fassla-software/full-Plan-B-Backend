<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratorOfferDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'generator_power',
        'max_number_of_continues_operating_houres',
        'number_of_daily_operating_houres',
        'generator_images',
    ];

    protected $casts = [
        'generator_images' => 'array',
    ];

    protected function generatorImages(): Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(json_decode($value ?: '[]'))
                ->map(fn($image) => asset('assets/uploads/generator-offer-details/' . $image))
                ->toArray(),

            set: fn($value) => json_encode($value)
        );
    }

    public function offer(): HasOne
    {
        return $this->hasOne(NewProposal::class, 'generator_offer_detail_id');
    }
}
