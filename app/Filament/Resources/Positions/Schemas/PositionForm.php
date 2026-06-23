<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('level')
                    ->options([
                        'DPP' => 'DPP (Pusat)',
                        'DPW' => 'DPW (Wilayah)',
                        'DPC' => 'DPC (Cabang)',
                        'PAC' => 'PAC (Kecamatan)',
                        'Ranting' => 'Ranting (Desa/Kelurahan)',
                    ])
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
