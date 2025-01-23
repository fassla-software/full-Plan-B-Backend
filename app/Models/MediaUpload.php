<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($id)
 */
class MediaUpload extends Model
{
    use HasFactory;
    protected $table = "media_uploads";
    protected $fillable = ['title','alt','size','path','dimensions','type','user_id','type','load_from','is_synced'];
}
