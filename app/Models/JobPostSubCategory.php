<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPostSubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['job_post_id','sub_category_id'];
}
