<?php

namespace App\Policies;

use App\Models\Regency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegencyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_regency');
    }

    public function view(User $user, Regency $regency): bool
    {
        return $user->hasPermissionTo('view_regency');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_regency');
    }

    public function update(User $user, Regency $regency): bool
    {
        return $user->hasPermissionTo('update_regency');
    }

    public function delete(User $user, Regency $regency): bool
    {
        return $user->hasPermissionTo('delete_regency');
    }
}
