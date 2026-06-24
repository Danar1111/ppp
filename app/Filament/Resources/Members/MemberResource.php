<?php

namespace App\Filament\Resources\Members;

use App\Filament\Resources\Members\Pages\CreateMember;
use App\Filament\Resources\Members\Pages\EditMember;
use App\Filament\Resources\Members\Pages\ListMembers;
use App\Filament\Resources\Members\Pages\ViewMember;
use App\Filament\Resources\Members\Schemas\MemberForm;
use App\Filament\Resources\Members\Tables\MembersTable;
use App\Models\User as Member;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen SDM';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $pluralModelLabel = 'Anggota';

    protected static ?string $modelLabel = 'Anggota';

    public static function form(Schema $schema): Schema
    {
        return MemberForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembersTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(['default' => 1, 'md' => 3])
                    ->schema([
                        // Left Column: Photo, Status Badge, QR Code (width: 1/3 on desktop)
                        Group::make([
                            Section::make('Identitas KTA')
                                ->schema([
                                    ImageEntry::make('photo')
                                        ->circular()
                                        ->height(140)
                                        ->width(140)
                                        ->alignment('center')
                                        ->disk('public')
                                        ->label(''),
                                    TextEntry::make('status')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'Aktif' => 'success',
                                            'Pending' => 'warning',
                                            'Nonaktif' => 'danger',
                                            default => 'gray',
                                        })
                                        ->alignment('center')
                                        ->label(''),
                                    ViewEntry::make('qr_code')
                                        ->view('filament.components.member-qr')
                                        ->label(''),
                                ]),
                        ])->columnSpan(1),

                        // Right Column: Personal & Regional Info (width: 2/3 on desktop)
                        Group::make([
                            Section::make('Informasi Pribadi')
                                ->description('Data diri lengkap anggota.')
                                ->icon('heroicon-o-user')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('name')
                                                ->label('Nama Lengkap')
                                                ->weight('bold'),
                                            TextEntry::make('nik')
                                                ->label('Nomor NIK')
                                                ->fontFamily('mono'),
                                            TextEntry::make('email')
                                                ->label('Alamat Email')
                                                ->placeholder('-'),
                                            TextEntry::make('phone')
                                                ->label('Nomor Telepon')
                                                ->placeholder('-'),
                                            TextEntry::make('address')
                                                ->label('Alamat Lengkap')
                                                ->columnSpan(2)
                                                ->placeholder('-'),
                                        ])
                                ]),

                            Section::make('Informasi Wilayah')
                                ->description('Asal wilayah administratif keanggotaan.')
                                ->icon('heroicon-o-map')
                                ->schema([
                                    Grid::make(2)
                                        ->schema([
                                            TextEntry::make('village.district.regency.province.name')
                                                ->label('Provinsi')
                                                ->placeholder('-'),
                                            TextEntry::make('village.district.regency.name')
                                                ->label('Kabupaten / Kota')
                                                ->placeholder('-'),
                                            TextEntry::make('village.district.name')
                                                ->label('Kecamatan')
                                                ->placeholder('-'),
                                            TextEntry::make('village.name')
                                                ->label('Kelurahan / Desa')
                                                ->placeholder('-'),
                                        ])
                                ]),
                        ])->columnSpan(2),
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'view' => ViewMember::route('/{record}'),
            'edit' => EditMember::route('/{record}/edit'),
        ];
    }
}
