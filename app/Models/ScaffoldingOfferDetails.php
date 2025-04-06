<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScaffoldingOfferDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_required_for_on_site_installation',
        'scaffolding_images',
    ];

    protected $casts = [
        'scaffolding_images' => 'array',
    ];

    protected function scaffoldingImages(): Attribute
    {
        return Attribute::make(
            get: fn($value) => collect(json_decode($value ?: '[]'))
                ->map(fn($image) => asset('assets/uploads/scaffolding-offer-details/' . $image))
                ->toArray(),

            set: fn($value) => json_encode($value)
        );
    }

    public function offer(): HasOne
    {
        return $this->hasOne(NewProposal::class, 'scaffolding_offer_detail_id');
    }
}
