<?php

namespace Modules\SupportTicket\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'admin_id',
        'client_id',
        'freelancer_id',
        'title',
        'subject',
        'priority',
        'description',
        'status',
        'via',
        'operating_system',
    ];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\TicketFactory::new();
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id','id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id','id');
    }

    public function message()
    {
        return $this->hasMany(ChatMessage::class, 'ticket_id','id');
    }

    public function get_ticket_latest_message()
    {
        return $this->hasOne(ChatMessage::class, 'ticket_id','id')->latest();
    }
}
