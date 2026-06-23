<?php

namespace App\Policies;

use App\Models\Committee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommitteePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_committee');
    }

    public function view(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('view_committee');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_committee');
    }

    public function update(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('update_committee');
    }

    public function delete(User $user, Committee $committee): bool
    {
        return $user->hasPermissionTo('delete_committee');
    }
}
