<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Nama Kegiatan / Agenda'),
                Textarea::make('description')
                    ->required()
                    ->label('Deskripsi Kegiatan')
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->required()
                    ->label('Nama Lokasi / Tempat (Textual)'),
                \Dotswan\MapPicker\Fields\Map::make('location_picker')
                    ->label('Pilih Titik Lokasi (Peta)')
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
                DateTimePicker::make('start_datetime')
                    ->required()
                    ->label('Waktu Mulai')
                    ->default(now()),
                DateTimePicker::make('end_datetime')
                    ->required()
                    ->label('Waktu Selesai')
                    ->default(now()->addHour()),
                Select::make('status')
                    ->options([
                        'Akan Datang' => 'Akan Datang',
                        'Sedang Berjalan' => 'Sedang Berjalan',
                        'Selesai' => 'Selesai',
                    ])
                    ->required()
                    ->default('Akan Datang')
                    ->label('Status Kegiatan'),
            ]);
    }
}
