<?php

namespace App\Models;

use App\Enums\BotType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TelegramBot extends Model
{
    protected $table = 'telegram_bots';

    protected $fillable = [
        'bot_name',
        'bot_type',
        'token',
        'channel_id',
    ];

    protected function casts(): array
    {
        return [
            'bot_type' => BotType::class,
        ];
    }

    public function sentElons(): HasMany
    {
        return $this->hasMany(TelegramSentElon::class);
    }

    /**
     * Elon yuborish uchun mo'ljallangan botni olish.
     */
    public static function elonSendChannel(): ?self
    {
        return static::where('bot_type', BotType::ElonSendChannel->value)->first();
    }
}
