<?php

namespace App\Filament\Resources\MoshinaElons\Pages;

use App\Filament\Resources\MoshinaElons\MoshinaElonResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMoshinaElons extends ManageRecords
{
    protected static string $resource = MoshinaElonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
