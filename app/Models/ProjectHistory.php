<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectHistory extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','user_id','reject_count','edit_count','reject_reason'];

    public function project()
    {
        return $this->hasOne(Project::class,'id','project_id');
    }

}
