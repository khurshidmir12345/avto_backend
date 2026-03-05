<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['user_a_id', 'user_b_id', 'last_message_at'];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    public function userA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_a_id');
    }

    public function userB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_b_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function getOtherUser(int $currentUserId): User
    {
        return $this->user_a_id === $currentUserId ? $this->userB : $this->userA;
    }

    public function getOtherUserId(int $currentUserId): int
    {
        return $this->user_a_id === $currentUserId ? $this->user_b_id : $this->user_a_id;
    }

    public static function findOrCreateBetween(int $userId1, int $userId2): self
    {
        $a = min($userId1, $userId2);
        $b = max($userId1, $userId2);

        return self::firstOrCreate(
            ['user_a_id' => $a, 'user_b_id' => $b],
            ['user_a_id' => $a, 'user_b_id' => $b]
        );
    }
}
