<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Models\Document;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Judul Dokumen'),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SK' => 'success',
                        'Surat Masuk' => 'info',
                        'Surat Keluar' => 'warning',
                        'Laporan' => 'gray',
                        default => 'neutral',
                    })
                    ->searchable()
                    ->label('Kategori'),
                TextColumn::make('file_path')
                    ->label('Arsip File')
                    ->formatStateUsing(fn () => 'Buka Dokumen')
                    ->url(fn (Document $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->icon('heroicon-o-arrow-top-right-on-square'),
                TextColumn::make('upload_date')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Arsip'),
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
