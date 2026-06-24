<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Tps;
use Illuminate\Auth\Access\HandlesAuthorization;

class TpsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Tps');
    }

    public function view(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('View:Tps');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Tps');
    }

    public function update(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('Update:Tps');
    }

    public function delete(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('Delete:Tps');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Tps');
    }

    public function restore(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('Restore:Tps');
    }

    public function forceDelete(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('ForceDelete:Tps');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Tps');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Tps');
    }

    public function replicate(AuthUser $authUser, Tps $tps): bool
    {
        return $authUser->can('Replicate:Tps');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Tps');
    }

}