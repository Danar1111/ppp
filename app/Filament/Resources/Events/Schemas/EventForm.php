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
                    ->label('Lokasi / Tempat'),
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
