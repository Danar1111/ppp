<?php

namespace App\Filament\Resources\Tps\Pages;

use App\Filament\Resources\Tps\TpsResource;
use Filament\Resources\Pages\EditRecord;

class EditTps extends EditRecord
{
    protected static string $resource = TpsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
