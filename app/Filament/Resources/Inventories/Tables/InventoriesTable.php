<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_code')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Barang'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Barang'),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable()
                    ->label('Jumlah'),
                TextColumn::make('condition')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baik' => 'success',
                        'Rusak Ringan' => 'warning',
                        'Rusak Berat' => 'danger',
                        default => 'gray',
                    })
                    ->label('Kondisi'),
                TextColumn::make('acquisition_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Perolehan'),
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
