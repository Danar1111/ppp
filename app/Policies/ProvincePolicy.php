<?php

namespace App\Policies;

use App\Models\Province;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProvincePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_province');
    }

    public function view(User $user, Province $province): bool
    {
        return $user->hasPermissionTo('view_province');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_province');
    }

    public function update(User $user, Province $province): bool
    {
        return $user->hasPermissionTo('update_province');
    }

    public function delete(User $user, Province $province): bool
    {
        return $user->hasPermissionTo('delete_province');
    }
}
