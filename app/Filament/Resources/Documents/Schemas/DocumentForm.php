<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->label('Judul Dokumen / Arsip')
                    ->placeholder('Masukkan judul dokumen...'),
                Select::make('category')
                    ->options([
                        'SK' => 'Surat Keputusan (SK)',
                        'Surat Masuk' => 'Surat Masuk',
                        'Surat Keluar' => 'Surat Keluar',
                        'Laporan' => 'Laporan Kegiatan',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required()
                    ->label('Kategori Dokumen'),
                FileUpload::make('file_path')
                    ->label('File Dokumen (PDF, DOC, DOCX)')
                    ->disk('public')
                    ->directory('organization-documents')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ])
                    ->required()
                    ->maxSize(10240), // 10MB max limit
                DatePicker::make('upload_date')
                    ->required()
                    ->label('Tanggal Arsip')
                    ->default(now()),
            ]);
    }
}
