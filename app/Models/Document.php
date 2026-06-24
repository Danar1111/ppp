<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'file_path',
        'upload_date',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::deleting(function ($document) {
            if ($document->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($document->file_path);
            }
        });

        static::updating(function ($document) {
            if ($document->isDirty('file_path')) {
                $oldFile = $document->getOriginal('file_path');
                if ($oldFile) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFile);
                }
            }
        });
    }
}
