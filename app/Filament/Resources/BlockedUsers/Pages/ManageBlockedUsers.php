<?php

namespace App\Filament\Resources\BlockedUsers\Pages;

use App\Filament\Resources\BlockedUsers\BlockedUserResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBlockedUsers extends ManageRecords
{
    protected static string $resource = BlockedUserResource::class;
}
