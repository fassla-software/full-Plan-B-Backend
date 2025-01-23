<?php

namespace Modules\Service\Entities;

use App\Models\JobPost;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubSubCategory extends Model
{
    use HasFactory;
    protected $fillable = ['sub_category','short_description','sub_category_id','status','slug','meta_title','meta_description','image'];
    protected $casts = ['status'=>'integer'];
	protected $tables = ['sub_sub_categories'];

    public static function all_sub_categories()
    {
        return self::select(['id','sub_category','status','image'])->where('status',1)->get();
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public function projects(){
        return $this->belongsToMany(Project::class,'project_sub_categories')->withTimestamps();
    }

    public function jobs(){
        return $this->belongsToMany(JobPost::class,'job_post_sub_categories')->withTimestamps();
    }

    public function skills()
    {
        return $this->hasMany(Skill::class,'sub_category_id','id');
    }

}
