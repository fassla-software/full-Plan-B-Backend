<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawGateway extends Model
{
    use HasFactory;

    protected $fillable = ['name','field','status'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WithdrawGatewayFactory::new();
    }
}
