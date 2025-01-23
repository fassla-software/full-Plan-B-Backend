<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['skill','category_id','sub_category_id','status'];
    protected $casts = ['status'=>'integer'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }

    public static function all_skills()
    {
        return self::select(['id','skill','status'])->where('status',1)->get();
    }

    public function jobs(){
        return $this->belongsToMany(JobPost::class,'job_post_skills');
    }
}
