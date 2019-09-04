<?php

namespace App\Policies;

use App\User;
use App\Organizador;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizerPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the organizador.
     *
     * @param  \App\User  $user
     * @param  \App\Organizador  $organizador
     * @return mixed
     */
    public function view(User $user, Organizador $organizador)
    {
        return $user->id === $organizador->id;
    }
}
