<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_document');
    }

    public function view(User $user, Document $document): bool
    {
        return $user->hasPermissionTo('view_document');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_document');
    }

    public function update(User $user, Document $document): bool
    {
        return $user->hasPermissionTo('update_document');
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->hasPermissionTo('delete_document');
    }
}
