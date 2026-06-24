<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nik',
        'phone',
        'address',
        'village_id',
        'photo',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasAnyRole([
                'Super Admin',
                'Admin Pusat (DPP)',
                'Admin Wilayah (DPW)',
                'Admin Cabang (DPC)',
                'Admin Kecamatan (PAC)',
            ]);
        }

        if ($panel->getId() === 'member') {
            return true;
        }

        return false;
    }

    protected static function booted(): void
    {
        static::deleting(function ($user) {
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
        });

        static::updating(function ($user) {
            if ($user->isDirty('photo')) {
                $oldPhoto = $user->getOriginal('photo');
                if ($oldPhoto) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPhoto);
                }
            }
        });
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    public function committees(): HasMany
    {
        return $this->hasMany(Committee::class, 'member_id');
    }
}
