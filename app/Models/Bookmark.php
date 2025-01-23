<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','identity','is_project_job'];

    public function bookmark_project()
    {
        return $this->belongsTo(Project::class,'identity','id')->whereHas('project_creator');
    }

    public function bookmark_job()
    {
        return $this->belongsTo(JobPost::class,'identity','id')->whereHas('job_creator');
    }
}
