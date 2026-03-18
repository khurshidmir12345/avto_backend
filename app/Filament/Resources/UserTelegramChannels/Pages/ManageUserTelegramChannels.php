<?php

namespace App\Filament\Resources\UserTelegramChannels\Pages;

use App\Filament\Resources\UserTelegramChannels\UserTelegramChannelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUserTelegramChannels extends ManageRecords
{
    protected static string $resource = UserTelegramChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Kanal qo\'shish'),
        ];
    }
}
