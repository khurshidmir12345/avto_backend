<?php

namespace App\Enums;

enum YoqilgiTuri: string
{
    case Benzin = 'benzin';
    case Metan = 'metan';
    case BenzinMetan = 'benzin+metan';
    case Dizel = 'dizel';
    case Elektr = 'elektr';
    case Gibrid = 'gibrid';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromConfig(): array
    {
        return config('moshina_elon.yoqilgi_turlari', self::values());
    }
}
