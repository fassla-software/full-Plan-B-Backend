<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'requestable_type', 'requestable_id'];
  
    public function requestable()
    {
        return $this->morphTo();
    }
}
