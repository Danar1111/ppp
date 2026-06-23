<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->length(16)
                    ->numeric()
                    ->label('NIK (Nomor Induk Kependudukan)'),
                TextInput::make('name')
                    ->required()
                    ->label('Nama Lengkap'),
                TextInput::make('phone')
                    ->tel()
                    ->label('Nomor Telepon'),
                Textarea::make('address')
                    ->required()
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
                Select::make('village_id')
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Kelurahan/Desa'),
                FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('member-photos')
                    ->label('Foto Profil')
                    ->imageEditor(),
                Select::make('status')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Pending' => 'Pending',
                        'Nonaktif' => 'Nonaktif',
                    ])
                    ->required()
                    ->default('Pending')
                    ->label('Status Keanggotaan'),
            ]);
    }
}
