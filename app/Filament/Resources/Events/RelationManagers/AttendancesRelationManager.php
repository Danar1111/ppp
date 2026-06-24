<?php

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $relatedResource = AttendanceResource::class;

    protected static ?string $title = 'Kehadiran';
    
    protected static ?string $modelLabel = 'Kehadiran';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->visible(fn ($livewire) => $livewire->pageClass === \App\Filament\Resources\Events\Pages\EditEvent::class),
            ]);
    }
}
