<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSubmitHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_milestone_id',
        'attachment',
        'status',
        'description',
    ];

    protected $casts = ['status'=>'integer'];

    public function request_revision()
    {
        return $this->hasOne(OrderRequestRevision::class,'order_submit_history_id','id');
    }
}
