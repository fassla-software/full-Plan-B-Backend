<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class IdentityVerification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','verify_by','country_id','state_id','city_id','address','zipcode','national_id_number','front_image','back_image','status','is_read','load_from',
        'is_synced'];
    protected $casts = ['status'=>'integer','is_read'=>'integer'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function user_country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function user_state()
    {
        return $this->belongsTo(State::class,'state_id');
    }
    public function user_city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
