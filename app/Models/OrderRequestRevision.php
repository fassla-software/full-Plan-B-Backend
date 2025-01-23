<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequestRevision extends Model
{
    use HasFactory;

    protected $fillable = ['order_submit_history_id','order_id','milestone_id','description'];
}
