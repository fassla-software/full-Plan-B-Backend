<?php

namespace Modules\Pages\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetaData extends Model
{
    use HasFactory;

    protected $fillable = ['meta_taggable_id','meta_taggable_type','meta_title','meta_tags','meta_description','facebook_meta_tags','facebook_meta_description','facebook_meta_image','twitter_meta_tags','twitter_meta_description','twitter_meta_image'];

    public function meta_taggable(){
        return $this->morphTo();
    }

    protected static function newFactory()
    {
        return \Modules\Pages\Database\factories\MetaDataFactory::new();
    }
}
