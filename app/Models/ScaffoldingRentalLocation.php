<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScaffoldingRentalLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'scaffolding_rental_id',
        'lat',
        'long',
        'current_equipment_location',
    ];

    public function scaffolding(): BelongsTo
    {
        return $this->belongsTo(ScaffoldingAndMetalFormworkRental::class, 'scaffolding_rental_id');
    }
}
