<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = ['identity','user_id','type','message','is_read'];

    public static function unread_notification()
    {
        return self::where('is_read','unread')->latest()->get();
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'identity','id');
    }
    public function job()
    {
        return $this->belongsTo(JobPost::class,'identity','id');
    }
}
