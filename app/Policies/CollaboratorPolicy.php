<?php

namespace App\Policies;

use App\User;
use App\Colaborador;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollaboratorPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the colaborador.
     *
     * @param  \App\User  $user
     * @param  \App\Colaborador  $colaborador
     * @return mixed
     */
    public function view(User $user, Colaborador $collaborator)
    {
        return $user->id === $collaborator->id;
    }

    /**
     * Determine whether the user can create colaboradors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the colaborador.
     *
     * @param  \App\User  $user
     * @param  \App\Colaborador  $colaborador
     * @return mixed
     */
    public function update(User $user, Colaborador $colaborador)
    {
        //
    }

    /**
     * Determine whether the user can delete the colaborador.
     *
     * @param  \App\User  $user
     * @param  \App\Colaborador  $colaborador
     * @return mixed
     */
    public function delete(User $user, Colaborador $colaborador)
    {
        //
    }
}
