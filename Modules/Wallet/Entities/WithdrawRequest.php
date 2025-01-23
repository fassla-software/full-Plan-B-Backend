<?php

namespace Modules\Wallet\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "amount",
        "gateway_id",
        "user_id",
        "status",
        "gateway_fields",
        "note",
        "image",
    ];
    protected $casts = ['status'=>'integer'];

    public function gateway_name()
    {
        return $this->belongsTo(WithdrawGateway::class,'gateway_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WithdrawRequestFactory::new();
    }
}
