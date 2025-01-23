<?php

namespace Modules\Chat\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveChat extends Model
{
    protected $fillable = [
        'client_id',
        'freelancer_id',
        'admin_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, "client_id","id");
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class,"freelancer_id","id");
    }

    public function livechatMessage(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id");
    }

    public function freelancer_unseen_msg(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id")
            ->where("live_chat_messages.from_user", 1)
            ->where("live_chat_messages.is_seen", 0);
    }

    public function client_unseen_msg(): HasMany
    {
        return $this->hasMany(LiveChatMessage::class,"live_chat_id","id")
            ->where("live_chat_messages.from_user", 2)->where("live_chat_messages.is_seen", 0);
    }
}
