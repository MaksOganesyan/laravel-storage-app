<?php

namespace App\Policies;

use App\Models\Thing;
use App\Models\User;

class ThingPolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin; 
    }

    public function view(User $user, Thing $thing)
    {
        return $user->is_admin; 
    }

    public function create(User $user)
    {
        return $user->is_admin; 
    }

    public function update(User $user, Thing $thing)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Thing $thing)
    {
        return $user->is_admin; 
    }
}
