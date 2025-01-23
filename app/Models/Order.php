<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Chat\Entities\Offer;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'identity',
        'is_project_job',
        'is_basic_standard_premium_custom',
        'revision',
        'revision_left',
        'delivery_time',
        'price',
        'commission_type',
        'commission_charge',
        'commission_amount',
        'transaction_type',
        'transaction_charge',
        'transaction_amount',
        'payable_amount',
        'payment_gateway',
        'payment_status',
        'manual_payment_image',
        'freelancer_id',
        'status',
        'description',
        'is_custom',
        'refund_amount',
        'refund_status',
        'payment_status',
        'is_fixed_hourly',
        'email_send',
        'order_type'
        ];

    protected $casts = ['status'=>'integer','is_custom'=>'integer','refund_status'=>'integer','status_before_hold'=>'integer'];

    public function user() //client
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class,'freelancer_id','id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'identity','id');
    }
    public function job()
    {
        return $this->belongsTo(JobPost::class,'identity','id');
    }

    public function order_mile_stones()
    {
        return $this->hasMany(OrderMilestone::class,'order_id','id');
    }

    public function order_submit_history()
    {
        return $this->hasMany(OrderSubmitHistory::class,'order_id','id');
    }

    public function order_request_revision()
    {
        return $this->hasMany(OrderRequestRevision::class,'order_id','id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class,'order_id','id');
    }

    public function hourly_work_history()
    {
        return $this->hasMany(OrderWorkHistory::class,'order_id','id');
    }

}
