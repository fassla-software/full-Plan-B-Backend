<?php

namespace Modules\Wallet\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','balance','remaining_balance','withdraw_amount','status'];
    protected $casts = ['status'=>'integer'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletFactory::new();
    }
}
