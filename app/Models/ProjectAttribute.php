<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'create_project_id',
        'type',
        'check_numeric_title',
        'basic_check_numeric',
        'standard_check_numeric',
        'premium_check_numeric',
    ];
}
