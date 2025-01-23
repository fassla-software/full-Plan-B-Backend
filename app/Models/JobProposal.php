<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Chat\Entities\LiveChat;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class JobProposal extends Model
{
    use HasFactory;

    protected $fillable = ['job_id','freelancer_id','client_id','amount','duration','revision','cover_letter','attachment','status','is_hired','is_short_listed','is_interview_take','load_from',
        'is_synced'];

    protected $casts = ['status'=>'integer','is_hired'=>'integer','is_short_listed'=>'integer','is_interview_take'=>'integer','is_view'=>'integer','is_rejected'=>'integer'];

    public function freelancer()
    {
        return $this->belongsTo(User::class,'freelancer_id','id');
    }

    public function job()
    {
        return $this->belongsTo(JobPost::class,'job_id','id');
    }

    public function freelancer_introduction_title_for_api(){
        return $this->hasOneThrough(UserIntroduction::class,User::class,"id","user_id","freelancer_id","id");
    }

    public function freelancer_country_for_api(){
        return $this->hasOneThrough(Country::class,User::class,"id","id","freelancer_id","country_id");
    }

    public function freelancer_state_for_api(){
        return $this->hasOneThrough(State::class,User::class,"id","id","freelancer_id","state_id");
    }

    public function live_chat_for_api()
    {
        return $this->belongsTo(LiveChat::class,'freelancer_id','freelancer_id')->where('client_id',auth()->user()->id);
    }

    public function complete_order_count_api()
    {
        return $this->hasMany(Order::class,'freelancer_id','freelancer_id')->where('status',3);
    }

    public function freelancer_ratings(): HasManyThrough
    {
        return $this->hasManyThrough(Rating::class, Order::class, 'freelancer_id','order_id','freelancer_id','id')->where('sender_type',1);
    }
}
