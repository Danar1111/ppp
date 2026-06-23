<?php

namespace App\Filament\Resources\Regencies\Pages;

use App\Filament\Resources\Regencies\RegencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRegencies extends ListRecords
{
    protected static string $resource = RegencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
