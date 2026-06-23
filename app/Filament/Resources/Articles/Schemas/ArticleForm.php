<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->label('Judul Berita'),
                TextInput::make('slug')
                    ->required()
                    ->unique('articles', 'slug', ignoreRecord: true)
                    ->label('Slug / URL'),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->label('Konten Berita'),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('articles')
                    ->label('Gambar Sampul'),
                Select::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                    ])
                    ->required()
                    ->default('Draft')
                    ->label('Status'),
                DateTimePicker::make('published_at')
                    ->label('Tanggal Publikasi')
                    ->default(now()),
            ]);
    }
}
