<?php

namespace App\Filament\Resources\ElonPrices\Pages;

use App\Filament\Resources\ElonPrices\ElonPriceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageElonPrices extends ManageRecords
{
    protected static string $resource = ElonPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
