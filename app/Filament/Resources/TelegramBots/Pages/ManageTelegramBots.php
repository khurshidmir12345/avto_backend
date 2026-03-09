<?php

namespace App\Filament\Resources\TelegramBots\Pages;

use App\Filament\Resources\TelegramBots\TelegramBotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTelegramBots extends ManageRecords
{
    protected static string $resource = TelegramBotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
