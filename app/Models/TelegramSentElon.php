<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramSentElon extends Model
{
    protected $fillable = [
        'moshina_elon_id',
        'user_id',
        'telegram_bot_id',
        'channel_id',
        'message_id',
    ];

    protected function casts(): array
    {
        return [
            'message_id' => 'integer',
        ];
    }

    public function moshinaElon(): BelongsTo
    {
        return $this->belongsTo(MoshinaElon::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function telegramBot(): BelongsTo
    {
        return $this->belongsTo(TelegramBot::class);
    }
}
