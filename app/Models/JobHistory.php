<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    use HasFactory;

    protected $fillable = ['job_id','user_id','reject_count','edit_count'];

    public function job()
    {
        return $this->hasOne(JobPost::class,'id','job_id');
    }
}
