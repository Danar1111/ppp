<?php

namespace App\Policies;

use App\Models\User as Member;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_member');
    }

    public function view(User $user, Member $member): bool
    {
        return $user->hasPermissionTo('view_member');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_member');
    }

    public function update(User $user, Member $member): bool
    {
        return $user->hasPermissionTo('update_member');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->hasPermissionTo('delete_member');
    }
}
