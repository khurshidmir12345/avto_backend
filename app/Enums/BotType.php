<?php

namespace App\Enums;

enum BotType: string
{
    case SetProfileBot = 'set_profile_bot';
    case ElonSendChannel = 'elon_send_channel';
    case Notification = 'notification';
    case Support = 'support';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::SetProfileBot => 'Profil ulash (Telegram)',
            self::ElonSendChannel => 'E\'lon yuborish (Kanal)',
            self::Notification => 'Bildirishnoma',
            self::Support => 'Qo\'llab-quvvatlash',
            self::Other => 'Boshqa',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->all();
    }
}
