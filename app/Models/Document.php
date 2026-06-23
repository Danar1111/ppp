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
}
