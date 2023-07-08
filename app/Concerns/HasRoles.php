<?php

namespace App\Concerns;

use App\Models\Role;

trait HasRoles
{
    public function roles()
    {
        // this relation to build relation many to many with role and Admin model and role with User model
        // authorizable this's morph name
        // role_user it's table we take from it morph felid name it authorizable
        return $this->morphToMany(Role::class, 'authorizable', 'role_user');
    }

    public function hasAbilities($ability)
    {
        // will return true or false mean if with him the authorization or no
        // exists() here return or false if accepted to condition
        // whereHas check is it contain on relation name it abilities
        //here the priority for deny
        $denied =  $this->roles()->whereHas('abilities',  function ($query) use ($ability) {
            // here query represent the conditions on relation name it abilities
            $query->where('ability', '=', $ability)
                ->where('type', '=', 'deny');
        })->exists();

        if ($denied) {
            return false;
        }


        return $this->roles()->whereHas('abilities',  function ($query) use ($ability) {
            // here query represent the conditions on relation name it abilities
            $query->where('ability', '=', $ability)
                ->where('type', '=', 'allow');
        })->exists();
    }
}
