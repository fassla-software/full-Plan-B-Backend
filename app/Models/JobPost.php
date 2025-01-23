<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'duration',
        'level',
        'description',
        'type',
        'hourly_rate',
        'estimated_hours',
        'budget',
        'tags',
        'attachment',
        'job_approve_request',
        'status',
        'last_seen',
        'last_apply_date',
        'meta_title',
        'meta_description',
        'meta_tags',
        'load_from',
        'is_synced'
    ];

    protected $casts = ['job_approve_request'=>'integer','status'=>'integer','on_off'=>'integer','current_status'=>'integer'];

    public function job_category(){
        return $this->belongsTo(Category::class,'category','id');
    }

    public function job_sub_categories(){
        return $this->belongsToMany(SubCategory::class,'job_post_sub_categories')->withTimestamps();
    }

    public function job_skills(){
        return $this->belongsToMany(Skill::class,'job_post_skills')->withTimestamps();
    }

    public function job_creator(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function job_history()
    {
        return $this->hasOne(JobHistory::class,'job_id','id');
    }

    public function job_proposals(){
        return $this->hasMany(JobProposal::class,'job_id','id')->latest()->whereHas('freelancer');
    }
}
