<?php

namespace App\Filament\Resources\Committees\Schemas;

use App\Models\Position;
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
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Clear territory fields when position changes
                        $set('province_id', null);
                        $set('regency_id', null);
                        $set('district_id', null);
                        $set('village_id', null);
                    })
                    ->label('Jabatan'),

                // DPP → Nasional, no territory selector needed
                // DPW → Province
                Select::make('province_id')
                    ->relationship('province', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Provinsi (Wilayah)')
                    ->placeholder('Pilih provinsi...')
                    ->visible(fn ($get): bool => self::positionHasLevel($get('position_id'), 'DPW'))
                    ->required(fn ($get): bool => self::positionHasLevel($get('position_id'), 'DPW')),

                // DPC → Regency/City
                Select::make('regency_id')
                    ->relationship('regency', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kabupaten / Kota (Cabang)')
                    ->placeholder('Pilih kabupaten / kota...')
                    ->visible(fn ($get): bool => self::positionHasLevel($get('position_id'), 'DPC'))
                    ->required(fn ($get): bool => self::positionHasLevel($get('position_id'), 'DPC')),

                // PAC → District
                Select::make('district_id')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kecamatan (PAC)')
                    ->placeholder('Pilih kecamatan...')
                    ->visible(fn ($get): bool => self::positionHasLevel($get('position_id'), 'PAC'))
                    ->required(fn ($get): bool => self::positionHasLevel($get('position_id'), 'PAC')),

                // Ranting → Village
                Select::make('village_id')
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Kelurahan / Desa (Ranting)')
                    ->placeholder('Pilih kelurahan / desa...')
                    ->visible(fn ($get): bool => self::positionHasLevel($get('position_id'), 'Ranting'))
                    ->required(fn ($get): bool => self::positionHasLevel($get('position_id'), 'Ranting')),

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

    /**
     * Checks whether the currently selected position has a specific level.
     */
    private static function positionHasLevel(?string $positionId, string $level): bool
    {
        if (!$positionId) return false;

        $position = Position::find($positionId);

        return $position && $position->level === $level;
    }
}
