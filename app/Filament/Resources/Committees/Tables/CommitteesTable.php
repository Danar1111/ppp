<?php

namespace App\Filament\Resources\Committees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommitteesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Anggota'),
                TextColumn::make('position.name')
                    ->searchable()
                    ->sortable()
                    ->label('Jabatan'),
                TextColumn::make('sk_number')
                    ->searchable()
                    ->label('Nomor SK'),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label('Masa Mulai'),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->label('Masa Selesai'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
