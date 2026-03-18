<?php

namespace App\Filament\Resources\TelegramChannels\Pages;

use App\Filament\Resources\TelegramChannels\TelegramChannelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTelegramChannels extends ManageRecords
{
    protected static string $resource = TelegramChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Kanal qo\'shish')
                ->mutateFormDataUsing(function (array $data): array {
                    if (!empty($data['avatar_path'])) {
                        $data['avatar_disk'] = config('moshina_elon.images.disk', 'r2');
                    }
                    return $data;
                }),
        ];
    }
}
