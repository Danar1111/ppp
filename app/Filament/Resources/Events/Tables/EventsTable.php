<?php

namespace App\Filament\Resources\Events\Tables;

use App\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kegiatan'),
                TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->label('Lokasi'),
                TextColumn::make('start_datetime')
                    ->dateTime()
                    ->sortable()
                    ->label('Waktu Mulai'),
                TextColumn::make('end_datetime')
                    ->dateTime()
                    ->sortable()
                    ->label('Waktu Selesai'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Akan Datang' => 'warning',
                        'Sedang Berjalan' => 'info',
                        'Selesai' => 'success',
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
            ->recordActions([
                EditAction::make(),
                Action::make('liveScanner')
                    ->label('Scanner Panitia')
                    ->icon('heroicon-o-camera')
                    ->color('success')
                    ->modalHeading(fn (Event $record) => 'Scanner Presensi: ' . $record->name)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Selesai')
                    ->modalContent(fn (Event $record): View => view(
                        'filament.pages.actions.event-live-scanner',
                        ['event' => $record]
                    )),
                Action::make('showQrProyektor')
                    ->label('QR Proyektor')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->modalHeading(fn (Event $record) => 'QR Code Proyektor: ' . $record->name)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn (Event $record): View => view(
                        'filament.pages.actions.event-qr-proyektor',
                        ['event' => $record]
                    )),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
