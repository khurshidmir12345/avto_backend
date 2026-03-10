<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_DRAFT = 'draft';

    public const MAX_DAILY_ADS = 10;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_key',
        'link',
        'status',
        'days',
        'daily_price',
        'total_price',
        'views',
        'started_at',
        'expires_at',
        'rejection_reason',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'days' => 'integer',
            'daily_price' => 'integer',
            'total_price' => 'integer',
            'views' => 'integer',
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED)
            ->where('expires_at', '>', now());
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_APPROVED
            && $this->expires_at
            && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_key)) {
            return null;
        }

        $cdnUrl = config('filesystems.disks.r2.url') ?? env('R2_PUBLIC_URL');
        if ($cdnUrl) {
            return rtrim($cdnUrl, '/') . '/' . ltrim($this->image_key, '/');
        }

        return rtrim(config('app.url'), '/') . '/media/' . ltrim($this->image_key, '/');
    }

    public static function getReklamaPrice(): int
    {
        return ElonPrice::where('key', 'reklama_create')->first()?->amount ?? 400_000;
    }

    public static function todayApprovedCount(): int
    {
        return self::where('status', self::STATUS_APPROVED)
            ->whereDate('started_at', today())
            ->count();
    }
}
