<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMilestone extends Model
{
    use HasFactory;

    protected $fillable =
        [
        'order_id',
        'title',
        'description',
        'price',
        'deadline',
        'revision',
        'revision_left',
    ];
    protected $casts = ['status'=>'integer'];

}
