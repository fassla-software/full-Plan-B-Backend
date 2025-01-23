<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeclineWalletHistory extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','freelancer_id','client_id','order_price','payment_status','cancel_or_decline','cancel_by'];
}
