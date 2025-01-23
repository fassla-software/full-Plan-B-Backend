<?php

namespace Modules\Pages\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','page_content','status','visibility','page_builder_status','layout','page_class','navbar_variant','breadcrumb_status','footer_variant'];

    protected static function newFactory()
    {
        return \Modules\Pages\Database\factories\PageFactory::new();
    }

    public function meta_data(){
        return $this->morphOne(MetaData::class,'meta_taggable');
    }
}
