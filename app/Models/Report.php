<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','client_id','freelancer_id','reporter','title','description','status','note'];
    protected $casts = ['status'=>'integer'];

    public function client()
    {
       return $this->belongsTo(user::class,'client_id','id');
    }
    public function freelancer()
    {
        return $this->belongsTo(user::class,'client_id','id');
    }

}
