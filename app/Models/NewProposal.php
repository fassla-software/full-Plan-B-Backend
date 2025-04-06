<?php

namespace App\Models;

use App\Models\GeneratorOfferDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id',
        'price',
        'per',
        'current_location',
        'offer_ends_at',
        'other_terms',
        'isSeen',
        'generator_offer_detail_id',
        'scaffolding_offer_detail_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected function setPriceAttribute($value)
    {
        $this->attributes['price'] = number_format((float) $value, 2, '.', '');
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generatorOfferDetails(): BelongsTo
    {
        return $this->belongsTo(GeneratorOfferDetails::class, 'generator_offer_detail_id');
    }

    public function scaffoldingOfferDetails(): BelongsTo
    {
        return $this->belongsTo(ScaffoldingOfferDetails::class, 'scaffolding_offer_detail_id');
    }
}
