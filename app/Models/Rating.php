<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','sender_id','sender_type','rating','review_feedback'];
    protected $casts = ['sender_type'=>'integer'];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function rating_details()
    {
        return $this->hasMany(Rating::class,'rating_id','id');
    }
}
