<?php

namespace App\Filament\Resources\Regencies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RegencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('province_id')
                    ->relationship('province', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code'),
            ]);
    }
}
