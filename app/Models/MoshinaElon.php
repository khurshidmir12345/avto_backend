<?php

namespace App\Models;

use App\Enums\ElonStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MoshinaElon extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'marka',
        'model',
        'yil',
        'probeg',
        'narx',
        'valyuta',
        'rang',
        'yoqilgi_turi',
        'uzatish_qutisi',
        'kraska_holati',
        'shahar',
        'telefon',
        'tavsif',
        'holati',
        'bank_kredit',
        'general',
    ];

    protected function casts(): array
    {
        return [
            'narx' => 'decimal:2',
            'bank_kredit' => 'boolean',
            'general' => 'boolean',
            'yil' => 'integer',
            'probeg' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class, 'car_id')->orderBy('sort_order');
    }

    public function telegramSentMessages(): HasMany
    {
        return $this->hasMany(TelegramSentElon::class);
    }

    public function isSold(): bool
    {
        return $this->holati === ElonStatus::Sold->value;
    }

    /**
     * Narxni formatlangan ko'rinishda qaytaradi: $7 900 yoki 79 000 000 so'm.
     */
    public function getFormattedNarxAttribute(): string
    {
        $amount = number_format((float) $this->narx, 0, '.', ' ');

        return match ($this->valyuta) {
            'USD' => "\${$amount}",
            'UZS' => "{$amount} so'm",
            default => $amount,
        };
    }

    /**
     * Probegni formatlangan ko'rinishda: 62 000 km.
     */
    public function getFormattedProbegAttribute(): string
    {
        return number_format($this->probeg, 0, '.', ' ') . ' km';
    }
}
