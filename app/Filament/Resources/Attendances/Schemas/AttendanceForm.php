<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'name'),
                Select::make('office_id')
                    ->relationship('office', 'name'),
                TextInput::make('type')
                    ->required()
                    ->default('Kegiatan'),
                DateTimePicker::make('scanned_at')
                    ->required(),
                TextInput::make('location_lat'),
                TextInput::make('location_lng'),
            ]);
    }
}
