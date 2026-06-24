<?php

namespace App\Filament\Resources\Documents\Tables;

use App\Models\Document;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
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
                    ->label('Format')
                    ->formatStateUsing(fn (string $state) => strtoupper(pathinfo($state, PATHINFO_EXTENSION)))
                    ->badge()
                    ->color(fn (string $state): string => match (strtoupper(pathinfo($state, PATHINFO_EXTENSION))) {
                        'PDF' => 'danger',
                        'DOC', 'DOCX' => 'primary',
                        default => 'neutral',
                    }),
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
            ->actions([
                Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading(fn (Document $record) => "Preview Dokumen: {$record->title}")
                    ->modalContent(fn (Document $record) => view('filament.components.document-preview', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl'),
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->recordAction('preview')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
