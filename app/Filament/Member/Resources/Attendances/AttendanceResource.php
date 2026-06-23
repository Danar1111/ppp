<?php

namespace App\Filament\Member\Resources\Attendances;

use App\Filament\Member\Resources\Attendances\Pages\ManageAttendances;
use App\Models\Attendance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Riwayat Kehadiran';

    protected static ?string $title = 'Riwayat Kehadiran';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('member_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Harian Kantor' => 'success',
                        'Kegiatan' => 'info',
                        default => 'gray',
                    })
                    ->label('Jenis Presensi'),
                TextColumn::make('event.name')
                    ->label('Kegiatan/Acara')
                    ->placeholder('-'),
                TextColumn::make('scanned_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Waktu Scan'),
                TextColumn::make('location_lat')
                    ->label('Latitude')
                    ->placeholder('-'),
                TextColumn::make('location_lng')
                    ->label('Longitude')
                    ->placeholder('-'),
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
            'index' => ManageAttendances::route('/'),
        ];
    }
}
