<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Subscription\Entities\UserSubscription;

class CommaConsume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_subscription_id',
        'operation_cost_id',
        'consumed_limit',
        'remaining_limit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user_subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class, 'user_subscription_id');
    }

    public function operation_cost(): BelongsTo
    {
        return $this->belongsTo(OperationCost::class, 'operation_cost_id');
    }
}
