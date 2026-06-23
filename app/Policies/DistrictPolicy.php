<?php

namespace App\Policies;

use App\Models\District;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DistrictPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_district');
    }

    public function view(User $user, District $district): bool
    {
        return $user->hasPermissionTo('view_district');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_district');
    }

    public function update(User $user, District $district): bool
    {
        return $user->hasPermissionTo('update_district');
    }

    public function delete(User $user, District $district): bool
    {
        return $user->hasPermissionTo('delete_district');
    }
}
