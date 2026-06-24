<?php

namespace App\Filament\Resources\Offices\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Dotswan\MapPicker\Fields\Map;

class OfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nama Kantor / Posko'),
                Select::make('type')
                    ->options([
                        'Utama' => 'Pusat/Utama (DPP)',
                        'Cabang' => 'Cabang/Wilayah (DPW/DPC)',
                        'Posko' => 'Posko Pemenangan (PAC/Posko)',
                    ])
                    ->required()
                    ->default('Posko')
                    ->label('Tipe Kantor'),
                Textarea::make('address')
                    ->required()
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
                TextInput::make('radius_meters')
                    ->numeric()
                    ->required()
                    ->default(50)
                    ->label('Radius Presensi (Meter)'),
                Map::make('location_picker')
                    ->label('Pilih Titik Lokasi Kantor (Peta)')
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
