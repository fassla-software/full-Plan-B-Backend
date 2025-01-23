<?php

namespace Modules\CountryManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['country','status'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\CountryManage\Database\factories\CountryFactory::new();
    }

    public static function all_countries()
    {
        return self::select(['id','country','status'])->where('status',1)->get();
    }
}
