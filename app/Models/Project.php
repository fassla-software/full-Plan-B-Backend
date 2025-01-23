<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'image',
        'basic_title',
        'standard_title',
        'premium_title',
        'basic_revision',
        'standard_revision',
        'premium_revision',
        'basic_delivery',
        'standard_delivery',
        'premium_delivery',
        'basic_regular_charge',
        'basic_discount_charge',
        'standard_regular_charge',
        'standard_discount_charge',
        'premium_regular_charge',
        'premium_discount_charge',
        'project_on_off',
        'project_approve_request',
        'status',
        'offer_packages_available_or_not',
        'is_pro',
        'pro_expire_date',
        'meta_title',
        'meta_description',
        'meta_tags',
        'load_from',
        'is_synced'
    ];

    protected $casts = [
        'status' => 'integer',
        'project_approve_request' => 'integer'
    ];

    public function project_attributes()
    {
        return $this->hasMany(ProjectAttribute::class,'create_project_id','id');
    }

    public function project_creator()
    {
        return $this->belongsTo(User::class,'user_id','id')->where('is_suspend',0);
    }

    public function project_history()
    {
        return $this->hasOne(ProjectHistory::class,'project_id','id');
    }

    public function project_category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function project_sub_categories(){
        return $this->belongsToMany(SubCategory::class,'project_sub_categories')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class,'identity','id');
    }

    public function complete_orders()
    {
        return $this->hasMany(Order::class,'identity','id')->where('status',3)->where('is_project_job','project');
    }

    public function ratings(){
        return $this->hasManyThrough(Rating::class,Order::class,"identity","order_id","id","id")
            ->where("is_project_job","project")->where('sender_type',1);
    }
}
