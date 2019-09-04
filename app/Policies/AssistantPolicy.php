<?php

namespace App\Policies;

use App\User;
use App\Asistente;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssistantPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determina cuando el usuario puede ver al asistente
     *
     * @param  \App\User  $user
     * @param  \App\Asistente  $assistant
     * @return mixed
     */
    public function view(User $user, Asistente $assistant)
    {
        return $user->id === $assistant->id;
    }

    /**
     * Determina cuando el usuario puede ver inscripciones.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function verInscripciones(User $user)
    {
        $asistente = Asistente::find($user->id);

        return $asistente->eventos()->count() > 0 ? true : false;
    }

}
