<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualCommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'admin_commission_type', 'admin_commission_charge'];
}
