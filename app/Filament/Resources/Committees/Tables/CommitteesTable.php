<?php

namespace App\Filament\Resources\Committees\Tables;

use App\Models\Committee;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommitteesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('member.photo')
                    ->circular()
                    ->defaultImageUrl(fn (Committee $record): string => 'https://ui-avatars.com/api/?name=' . urlencode($record->member->name ?? 'A') . '&background=0D8A4E&color=fff')
                    ->label('Foto'),

                TextColumn::make('member.name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Anggota'),

                TextColumn::make('position.name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (Committee $record): string => match ($record->position?->level) {
                        'DPP'     => 'danger',
                        'DPW'     => 'warning',
                        'DPC'     => 'success',
                        'PAC'     => 'info',
                        'Ranting' => 'gray',
                        default   => 'neutral',
                    })
                    ->label('Jabatan'),

                TextColumn::make('territory_label')
                    ->getStateUsing(fn (Committee $record): string => match ($record->position?->level) {
                        'DPP'     => 'Pusat / Nasional',
                        'DPW'     => $record->province->name ?? 'Provinsi Tidak Diketahui',
                        'DPC'     => $record->regency->name ?? 'Kab/Kota Tidak Diketahui',
                        'PAC'     => 'Kec. ' . ($record->district->name ?? 'Tidak Diketahui'),
                        'Ranting' => 'Desa ' . ($record->village->name ?? 'Tidak Diketahui'),
                        default   => 'Tidak Diketahui',
                    })
                    ->icon(fn (Committee $record): string => match ($record->position?->level) {
                        'Ranting' => 'heroicon-o-home',
                        'PAC'     => 'heroicon-o-map-pin',
                        'DPC'     => 'heroicon-o-building-office',
                        'DPW'     => 'heroicon-o-globe-alt',
                        default   => 'heroicon-o-flag',
                    })
                    ->label('Wilayah / Daerah'),

                TextColumn::make('sk_number')
                    ->searchable()
                    ->label('Nomor SK')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('Masa Mulai'),

                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('Masa Selesai')
                    ->placeholder('Aktif'),
            ])
            ->filters([
                SelectFilter::make('province_id')
                    ->label('Provinsi')
                    ->relationship('province', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('regency_id')
                    ->label('Kabupaten/Kota')
                    ->relationship('regency', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('village_id')
                    ->label('Desa/Kelurahan')
                    ->relationship('village', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
