<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{

    /**
     * Determine whether the user can view any models.
     */

    // here mean find me authorization to see all roles
    // and here if method return true be user with him authorization if no be not authorization
    public function viewAny($user): bool
    {
        return $user->hasAbilities('roles.view');
    }

    /**
     * Determine whether the user can view the model.
     */

    // here mean find me authorization to see all roles except specific role
    public function view($user, Role $role): bool
    {
        return $user->hasAbilities('roles.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        return $user->hasAbilities('roles.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Role $role): bool
    {
        return $user->hasAbilities('roles.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Role $role): bool
    {
        return $user->hasAbilities('roles.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */

    // here if I need make restore for specific role
    public function restore($user, Role $role): bool
    {
        return $user->hasAbilities('roles.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete($user, Role $role): bool
    {
        return $user->hasAbilities('roles.force-delete');
    }
}
