<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_DISMISSED = 'dismissed';

    public const REASON_SPAM = 'spam';
    public const REASON_INAPPROPRIATE = 'inappropriate';
    public const REASON_FRAUD = 'fraud';
    public const REASON_OFFENSIVE = 'offensive';
    public const REASON_OTHER = 'other';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_REVIEWED,
        self::STATUS_RESOLVED,
        self::STATUS_DISMISSED,
    ];

    public const REASONS = [
        self::REASON_SPAM,
        self::REASON_INAPPROPRIATE,
        self::REASON_FRAUD,
        self::REASON_OFFENSIVE,
        self::REASON_OTHER,
    ];

    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public static function getReportableTypes(): array
    {
        return [
            'elon' => MoshinaElon::class,
            'user' => User::class,
            'message' => Message::class,
        ];
    }

    public static function resolveReportableType(string $type): ?string
    {
        return self::getReportableTypes()[$type] ?? null;
    }
}
