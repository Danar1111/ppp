<?php

namespace App\Filament\Resources\Tps;

use App\Filament\Resources\Tps\Pages\CreateTps;
use App\Filament\Resources\Tps\Pages\EditTps;
use App\Filament\Resources\Tps\Pages\ListTps;
use App\Filament\Resources\Tps\Schemas\TpsForm;
use App\Filament\Resources\Tps\Tables\TpsTable;
use App\Models\Tps;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TpsResource extends Resource
{
    protected static ?string $model = Tps::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'Pemenangan Pemilu';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'TPS';

    protected static ?string $pluralModelLabel = 'TPS';

    public static function form(Schema $schema): Schema
    {
        return TpsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TpsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTps::route('/'),
            'create' => CreateTps::route('/create'),
            'edit' => EditTps::route('/{record}/edit'),
        ];
    }
}
