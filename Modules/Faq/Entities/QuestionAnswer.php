<?php

namespace Modules\Faq\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['question','answer'];
    
    protected static function newFactory()
    {
        return \Modules\Faq\Database\factories\QuestionAnswerFactory::new();
    }
}
