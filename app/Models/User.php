<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Chat\Entities\LiveChat;
use Modules\Chat\Entities\LiveChatMessage;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Wallet\Entities\Wallet;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, softDeletes, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'hourly_rate',
        'experience_level',
        'email',
        'phone',
        'username',
        'password',
        'user_type',
        'country_id',
        'email_verify_token',
        'is_email_verified',
        'google_2fa_secret',
        'google_2fa_enable_disable_disable',
        'google_id',
        'facebook_id',
        'apple_id',
        'load_from',
        'is_synced'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'check_online_status'=>'datetime',
        'user_type'=>'integer',
        'check_work_availability'=>'integer',
        'user_active_inactive_status'=>'integer',
        'user_verified_status'=>'integer',
        'is_suspend'=>'integer',
        'google_2fa_enable_disable_disable'=>'integer',
    ];

    //get user full name
    public function getFullnameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    //google 2fa
    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  decrypt($value),
            set: fn ($value) =>  encrypt($value),
        );
    }

    public function user_country()
    {
        return $this->belongsTo(Country::class,'country_id')->select('id','country','status');
    }
    public function user_state()
    {
        return $this->belongsTo(State::class,'state_id');
    }
    public function user_city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function user_introduction()
    {
        return $this->hasOne(UserIntroduction::class,'user_id');
    }
    public function identity_verify()
    {
        return $this->hasOne(IdentityVerification::class,'user_id','id');
    }

    public function user_jobs()
    {
        return $this->hasMany(JobPost::class,'user_id','id');
    }

    public function user_complete_orders()
    {
        return $this->hasMany(Order::class,'user_id','id')->where('status',3);
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'user_id','id');
    }

    public function user_wallet()
    {
        return $this->hasOne(Wallet::class,'user_id','id');
    }

    public function admin_commission()
    {
        return $this->hasOne(IndividualCommissionSetting::class,'user_id','id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class,'user_id','id');
    }

    public function freelancer_orders()
    {
        return $this->hasMany(Order::class,'freelancer_id','id');
    }

    public function freelancer_category()
    {
        return $this->hasMany(UserWork::class,'user_id','id');
    }

    public function freelancer_subcategory()
    {
        return $this->hasMany(UserWork::class,'user_id','id');
    }

    public function freelancer_skill()
    {
        return $this->hasMany(UserSkill::class,'user_id','id');
    }

    public function freelancer_ratings(): HasManyThrough
    {
        return $this->hasManyThrough(Rating::class, Order::class,'freelancer_id','order_id');
    }

    public function freelancer_unseen_message(): HasManyThrough
    {
        return $this->hasManyThrough(LiveChatMessage::class, LiveChat::class,'freelancer_id','live_chat_id');
    }

    public function client_unseen_message(): HasManyThrough
    {
        return $this->hasManyThrough(LiveChatMessage::class, LiveChat::class,'client_id','live_chat_id');
    }
}
