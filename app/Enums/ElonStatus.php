<?php

namespace App\Enums;

enum ElonStatus: string
{
    case Active = 'active';
    case Sold = 'sold';
    case Inactive = 'inactive';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromConfig(): array
    {
        $config = config('moshina_elon.holatlar');
        return is_array($config) ? array_values($config) : self::values();
    }
}
