<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
