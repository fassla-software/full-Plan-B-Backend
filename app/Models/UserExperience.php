<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','title','short_description','organization','address','country_id','state_id','start_date','end_date'];
    protected $dates = ['start_date','end_date'];
}
