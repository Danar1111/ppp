<?php

namespace App\Filament\Member\Resources\Events;

use App\Filament\Member\Resources\Events\Pages\ManageEvents;
use App\Models\Event;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Agenda Partai';

    protected static ?string $title = 'Agenda Partai';

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Kegiatan'),
                TextColumn::make('location')
                    ->searchable()
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Read-only
            ])
            ->toolbarActions([
                // Read-only
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageEvents::route('/'),
        ];
    }
}
