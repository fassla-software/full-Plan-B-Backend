<?php

namespace Modules\CountryManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['state','country_id','status','timezone'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\CountryManage\Database\factories\StateFactory::new();
    }

    public static function all_states()
    {
        return self::select(['id','state','country_id','status'])->where('status',1)->get();
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
