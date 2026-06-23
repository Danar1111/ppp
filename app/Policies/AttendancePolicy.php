<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_attendance');
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $user->hasPermissionTo('view_attendance');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_attendance');
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->hasPermissionTo('update_attendance');
    }

    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->hasPermissionTo('delete_attendance');
    }
}
