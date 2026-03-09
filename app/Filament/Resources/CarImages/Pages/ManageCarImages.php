<?php

namespace App\Filament\Resources\CarImages\Pages;

use App\Filament\Resources\CarImages\CarImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCarImages extends ManageRecords
{
    protected static string $resource = CarImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
