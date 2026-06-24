<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Committee extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'position_id',
        'sk_number',
        'start_date',
        'end_date',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Returns a human-readable summary of the territorial assignment.
     */
    public function getTerritoryLabelAttribute(): string
    {
        if ($this->village_id && $this->village)   return 'Kel. ' . $this->village->name;
        if ($this->district_id && $this->district) return 'Kec. ' . $this->district->name;
        if ($this->regency_id && $this->regency)   return 'Kab/Kota ' . $this->regency->name;
        if ($this->province_id && $this->province) return 'Prov. ' . $this->province->name;
        return 'Nasional';
    }
}
