<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tps extends Model
{
    use HasFactory;

    protected $table = 'tps';

    protected $fillable = [
        'name',
        'village_id',
        'latitude',
        'longitude',
        'status',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}
