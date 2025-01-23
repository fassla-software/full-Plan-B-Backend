<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = ['freelancer_id','client_id','price','description','deadline','status','revision','revision_left','attachment'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\OfferFactory::new();
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class,'freelancer_id','id');
    }

    public function client()
    {
        return $this->belongsTo(User::class,'client_id','id');
    }

    public function milestones()
    {
        return $this->hasMany(OfferMilestone::class,'offer_id','id');
    }
}
