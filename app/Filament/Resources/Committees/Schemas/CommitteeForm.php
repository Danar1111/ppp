<?php

namespace App\Filament\Resources\Committees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CommitteeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_id')
                    ->relationship('member', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Nama Anggota'),
                Select::make('position_id')
                    ->relationship('position', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Jabatan'),
                TextInput::make('sk_number')
                    ->label('Nomor Surat Keputusan (SK)')
                    ->placeholder('Contoh: SK/DPP-PPP/VI/2026')
                    ->maxLength(255),
                DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai Masa Jabatan')
                    ->default(now()),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai Masa Jabatan (Opsional)'),
            ]);
    }
}
