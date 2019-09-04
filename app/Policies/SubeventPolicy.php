<?php

namespace App\Policies;

use App\User;
use App\Evento;
use App\Asistente;
use App\Subevento;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubeventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function agregarColaborador(User $user, Subevento $subevent)
    {
        return $user->id === Evento::find($subevent->evento_id)->organizador_id;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function quitarColaborador(User $user, Subevento $subevent)
    {
        return $user->id === Evento::find($subevent->evento_id)->organizador_id;
    }

    /**
     * Determina si el usuario puede agregar precio al subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function agregarPrecio(User $user, Subevento $subevent)
    {
        return $user->id === Evento::find($subevent->evento_id)->organizador_id;
    }

    /**
     * Determina si el usuario puede quitar precio al subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function quitarPrecio(User $user, Subevento $subevent)
    {
        return $user->id === Evento::find($subevent->evento_id)->organizador_id;
    }

    /**
     * Determina si el usuario puede actualizar precio al subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function actualizarPrecio(User $user, Subevento $subevent)
    {
        return $user->id === Evento::find($subevent->evento_id)->organizador_id;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function verAsistente(User $user, Subevento $subevent, Asistente $assistant)
    {
        //si es el asistente
        if($user->id === $assistant->id){
            return true;
        }

        //Si es el organizador del evento
        if($user->id === Evento::find($subevent->evento_id)->organizador_id){
            return true;
        }
        //Si es colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($user->id);
        if(isset($colaborador)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function listarAsistentes(User $user, Subevento $subevent)
    {
        //Si es el organizador del evento
        if($user->id === Evento::find($subevent->evento_id)->organizador_id){
            return true;
        }
        //Si es colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($user->id);
        if(isset($colaborador)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function actualizarAsistente(User $user, Subevento $subevent)
    {
        //Si es el organizador del evento
        if($user->id === Evento::find($subevent->evento_id)->organizador_id){
            return true;
        }
        //Si es colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($user->id);
        if(isset($colaborador)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function eliminarAsistente(User $user, Subevento $subevent, Asistente $assistant)
    {
        //si es el asistente
        if($user->id === $assistant->id){
            return true;
        }

        //Si es el organizador del evento
        if($user->id === Evento::find($subevent->evento_id)->organizador_id){
            return true;
        }
        //Si es colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($user->id);
        if(isset($colaborador)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the subevento.
     *
     * @param  \App\User  $user
     * @param  \App\Subevento  $subevento
     * @return mixed
     */
    public function enviarMensajeAsistente(User $user, Subevento $subevent)
    {
        //Si es el organizador del evento
        if($user->id === Evento::find($subevent->evento_id)->organizador_id){
            return true;
        }
        //Si es colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($user->id);
        if(isset($colaborador)){
            return true;
        }

        return false;
    }
}
