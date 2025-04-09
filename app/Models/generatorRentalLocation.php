<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class generatorRentalLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'generator_rental_id',
        'lat',
        'long',
        'current_equipment_location',
    ];

    public function generator(): BelongsTo
    {
        return $this->belongsTo(GeneratorRental::class, 'generator_rental_id');
    }
}
