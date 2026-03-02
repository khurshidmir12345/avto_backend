<?php

namespace App\Enums;

enum UzatishQutisi: string
{
    case Mexanika = 'mexanika';
    case Avtomat = 'avtomat';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromConfig(): array
    {
        return config('moshina_elon.uzatish_qutisi_turlari', self::values());
    }
}
