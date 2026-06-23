<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_inventory');
    }

    public function view(User $user, Inventory $inventory): bool
    {
        return $user->hasPermissionTo('view_inventory');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_inventory');
    }

    public function update(User $user, Inventory $inventory): bool
    {
        return $user->hasPermissionTo('update_inventory');
    }

    public function delete(User $user, Inventory $inventory): bool
    {
        return $user->hasPermissionTo('delete_inventory');
    }
}
