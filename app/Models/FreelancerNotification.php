<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerNotification extends Model
{
    use HasFactory;

    protected $fillable = ['identity','freelancer_id','type','message','is_read'];
}
