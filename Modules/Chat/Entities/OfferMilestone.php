<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfferMilestone extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id','title','price','description','deadline','status','revision','revision_left','attachment'];
    protected $casts = ['status'=>'integer'];

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\OfferMilestoneFactory::new();
    }
}
