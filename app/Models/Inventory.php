<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'name',
        'quantity',
        'condition',
        'acquisition_date',
        'notes',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
    ];
}
