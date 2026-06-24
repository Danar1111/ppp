<?php

namespace App\Filament\Resources\Tps\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Dotswan\MapPicker\Fields\Map;

class TpsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nama TPS (Tempat Pemungutan Suara)'),
                Select::make('village_id')
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Kelurahan/Desa'),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Selesai' => 'Selesai',
                    ])
                    ->required()
                    ->default('Pending')
                    ->label('Status TPS'),
                Map::make('location_picker')
                    ->label('Pilih Koordinat TPS (Peta)')
                    ->columnSpanFull()
                    ->defaultLocation(latitude: -6.8388, longitude: 107.9253)
                    ->live()
                    ->view('filament.components.custom-map-picker')
                    ->afterStateHydrated(function ($state, $record, $set): void {
                        $set('location_picker', [
                            'lat' => $record?->latitude ?? -6.8388,
                            'lng' => $record?->longitude ?? 107.9253,
                        ]);
                    })
                    ->afterStateUpdated(function ($state, $set): void {
                        $set('latitude', $state['lat'] ?? null);
                        $set('longitude', $state['lng'] ?? null);
                    })
                    ->dehydrated(false),
                TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function ($state, $get, $set): void {
                        $set('location_picker', [
                            'lat' => $state,
                            'lng' => $get('longitude'),
                        ]);
                    }),
                TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->numeric()
                    ->live()
                    ->afterStateUpdated(function ($state, $get, $set): void {
                        $set('location_picker', [
                            'lat' => $get('latitude'),
                            'lng' => $state,
                        ]);
                    }),
            ]);
    }
}
