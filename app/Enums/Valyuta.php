<?php

namespace App\Enums;

enum Valyuta: string
{
    case USD = 'USD';
    case UZS = 'UZS';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromConfig(): array
    {
        return config('moshina_elon.valyutalar', self::values());
    }
}
