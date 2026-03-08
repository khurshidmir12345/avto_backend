<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElonPrice extends Model
{
    public const KEY_ELON_CREATE = 'elon_create';

    protected $fillable = ['key', 'amount'];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public static function getElonCreatePrice(): int
    {
        $row = self::where('key', self::KEY_ELON_CREATE)->first();

        return $row?->amount ?? 25_000;
    }
}
