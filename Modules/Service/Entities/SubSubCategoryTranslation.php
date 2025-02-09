<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubSubCategoryTranslation extends Model
{
    protected $fillable = ['sub_sub_category_id', 'locale', 'name'];
    public $timestamps = false;

    public function subSubCategory(): BelongsTo
    {
        return $this->belongsTo(SubSubCategory::class);
    }
}
