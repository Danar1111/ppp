<?php

namespace App\Filament\Resources\Members\Tables;

use App\Models\User as Member;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->disk('public')
                    ->label('Foto'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama')
                    ->description(fn (Member $record): string => $record->nik),
                TextColumn::make('phone')
                    ->searchable()
                    ->label('Telepon'),
                TextColumn::make('village.name')
                    ->searchable()
                    ->label('Kelurahan/Desa'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Pending' => 'warning',
                        'Nonaktif' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),
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
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor Excel'),
            ])
            ->actions([
                Action::make('lihat_kta')
                    ->label('Lihat KTA')
                    ->icon('heroicon-o-identification')
                    ->color('success')
                    ->modalContent(fn (Member $record) => view('components.kta-card', ['member' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Ekspor Pilihan'),
                ]),
            ]);
    }
}
