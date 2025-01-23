<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderWorkHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','client_id','freelancer_id','job_id','start_date','end_date','hours_worked','notes','only_start_date','only_end_date','seconds'];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}