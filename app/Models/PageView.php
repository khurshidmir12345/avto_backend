<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'page',
        'device_id',
        'platform',
        'view_date',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'view_date' => 'date',
            'created_at' => 'datetime',
        ];
    }
}
