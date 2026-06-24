<?php

namespace App\Filament\Resources\Committees;

use App\Filament\Resources\Committees\Pages\CreateCommittee;
use App\Filament\Resources\Committees\Pages\EditCommittee;
use App\Filament\Resources\Committees\Pages\ListCommittees;
use App\Filament\Resources\Committees\Schemas\CommitteeForm;
use App\Filament\Resources\Committees\Tables\CommitteesTable;
use App\Models\Committee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CommitteeResource extends Resource
{
    protected static ?string $model = Committee::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen SDM';

    protected static ?int $navigationSort = 2;

    protected static ?string $pluralModelLabel = 'Pengurus';

    protected static ?string $modelLabel = 'Pengurus';

    public static function form(Schema $schema): Schema
    {
        return CommitteeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommitteesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommittees::route('/'),
            'create' => CreateCommittee::route('/create'),
            'edit' => EditCommittee::route('/{record}/edit'),
        ];
    }
}
