<?php

namespace App\Enums;

enum YoqilgiTuri: string
{
    case Benzin = 'benzin';
    case BenzinMetan = 'benzin+metan';
    case BenzinPropan = 'benzin+propan';
    case Dizel = 'dizel';
    case Salarka = 'salarka';
    case Eloktor = 'eloktor';
    case Gibrid = 'gibrid';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            'benzin' => 'Benzin',
            'benzin+metan' => 'Benzin + Metan',
            'benzin+propan' => 'Benzin + Propan',
            'dizel' => 'Dizel',
            'salarka' => 'Salarka',
            'eloktor' => 'Eloktor',
            'gibrid' => 'Gibrid',
        ];
    }

    public static function fromConfig(): array
    {
        $config = config('moshina_elon.yoqilgi_turlari', self::values());

        return array_is_list($config) ? $config : array_keys($config);
    }
}
