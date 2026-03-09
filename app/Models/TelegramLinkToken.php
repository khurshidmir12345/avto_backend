<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramLinkToken extends Model
{
    protected $fillable = [
        'token',
        'telegram_user_id',
        'telegram_username',
        'telegram_first_name',
        'telegram_last_name',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
