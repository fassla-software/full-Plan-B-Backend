<?php

namespace Modules\Subscription\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','subscription_id','price','limit','expire_date','payment_gateway','payment_status','status','transaction_id','manual_payment_image','email_send'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\Subscription\Database\factories\UserSubscriptionFactory::new();
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function user_subscription_type_api(): HasOneThrough
    {
        return $this->hasOneThrough(SubscriptionType::class, Subscription::class, 'id', 'id','subscription_id','subscription_type_id')
            ->select('subscription_types.id as typeID', 'subscription_types.type');
    }
}
