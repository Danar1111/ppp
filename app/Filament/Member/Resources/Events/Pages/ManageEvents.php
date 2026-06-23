<?php

namespace App\Filament\Member\Resources\Events\Pages;

use App\Filament\Member\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEvents extends ManageRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Read-only
        ];
    }
}
