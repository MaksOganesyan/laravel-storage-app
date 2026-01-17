<?php

namespace App\Policies;

use App\Models\Place;
use App\Models\User;

class PlacePolicy
{
    public function viewAny(User $user)
    {
        return $user->is_admin;
    }

    public function create(User $user)
    {
        return $user->is_admin;
    }

    public function update(User $user, Place $place)
    {
        return $user->is_admin;
    }

    public function delete(User $user, Place $place)
    {
        return $user->is_admin;
    }
}
