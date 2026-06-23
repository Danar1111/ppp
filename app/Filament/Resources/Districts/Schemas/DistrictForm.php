<?php

namespace App\Filament\Resources\Districts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DistrictForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('regency_id')
                    ->relationship('regency', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code'),
            ]);
    }
}
