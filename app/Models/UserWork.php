<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Service\Entities\Category;
use Modules\Service\Entities\SubCategory;

class UserWork extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','category_id','sub_category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class,'sub_category_id');
    }
}
