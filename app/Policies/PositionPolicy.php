<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_position');
    }

    public function view(User $user, Position $position): bool
    {
        return $user->hasPermissionTo('view_position');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_position');
    }

    public function update(User $user, Position $position): bool
    {
        return $user->hasPermissionTo('update_position');
    }

    public function delete(User $user, Position $position): bool
    {
        return $user->hasPermissionTo('delete_position');
    }
}
