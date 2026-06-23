<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Village;
use Illuminate\Auth\Access\HandlesAuthorization;

class VillagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_village');
    }

    public function view(User $user, Village $village): bool
    {
        return $user->hasPermissionTo('view_village');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_village');
    }

    public function update(User $user, Village $village): bool
    {
        return $user->hasPermissionTo('update_village');
    }

    public function delete(User $user, Village $village): bool
    {
        return $user->hasPermissionTo('delete_village');
    }
}
