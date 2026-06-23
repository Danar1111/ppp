<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Kode Barang / Aset')
                    ->placeholder('Contoh: AST-001'),
                TextInput::make('name')
                    ->required()
                    ->label('Nama Barang / Aset'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->label('Jumlah (Qty)')
                    ->minValue(1),
                Select::make('condition')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak Ringan' => 'Rusak Ringan',
                        'Rusak Berat' => 'Rusak Berat',
                    ])
                    ->required()
                    ->label('Kondisi'),
                DatePicker::make('acquisition_date')
                    ->required()
                    ->label('Tanggal Perolehan')
                    ->default(now()),
                Textarea::make('notes')
                    ->label('Catatan Keterangan')
                    ->columnSpanFull(),
            ]);
    }
}
