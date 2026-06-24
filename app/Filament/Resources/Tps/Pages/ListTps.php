<?php

namespace App\Filament\Resources\Tps\Pages;

use App\Filament\Resources\Tps\TpsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTps extends ListRecords
{
    protected static string $resource = TpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
