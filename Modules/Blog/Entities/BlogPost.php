<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pages\Entities\MetaData;
use Modules\Service\Entities\Category;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = ['category_id',
        'admin_id','title','slug','blog_content',
        'image','status','views',
        'tag_name'
    ];
    protected $casts = ['status'=>'integer'];
    
    protected static function newFactory()
    {
        return \Modules\Blog\Database\factories\BlogPostFactory::new();
    }

    public function meta_data(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
