<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'logo',
        'phone',
        'whatsapp',
        'email',
        'description',
        'status'
    ];
}
