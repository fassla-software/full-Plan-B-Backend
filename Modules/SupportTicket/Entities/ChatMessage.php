<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_id','message','attachment','notify','type','load_from','is_synced'];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\ChatMessageFactory::new();
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'ticket_id','id');
    }
}
