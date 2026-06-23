<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_event');
    }

    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('view_event');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_event');
    }

    public function update(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('update_event');
    }

    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('delete_event');
    }
}
