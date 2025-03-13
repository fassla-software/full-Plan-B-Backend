<?php

namespace App\Models;

use App\Models\NewProposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Request extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'requestable_type', 'requestable_id'];

    public function requestable()
    {
        return $this->morphTo();
    }

    public function newProposals()
    {
        return $this->hasMany(NewProposal::class, 'request_id');
    }
}
