<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTelegramChannel extends Model
{
    protected $fillable = [
        'user_id',
        'bot_token',
        'chat_id',
        'channel_name',
        'channel_username',
        'message_template',
        'footer_text',
        'is_active',
        'last_error_at',
        'last_error_message',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_error_at' => 'datetime',
        ];
    }

    protected $hidden = [
        'bot_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDefaultTemplate(): string
    {
        return <<<'TPL'
♻️ {hashtag} Сотилади ♻️

🚗 Модел: {marka} {model}
📆 Йил: {yil} йил
📏 Пробег: {probeg}
💰 Нархи: {narx}
📞 Тел: +{telefon}
📍 Манзил: {shahar}

{footer}

👉 Кўриш: {link}
TPL;
    }

    public function getEffectiveTemplate(): string
    {
        return !empty($this->message_template)
            ? $this->message_template
            : $this->getDefaultTemplate();
    }
}
