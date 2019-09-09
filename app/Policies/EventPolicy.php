<?php

namespace App\Policies;

use App\User;
use App\Evento;
use App\Asistente;
use App\Colaborador;
use App\Organizador;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determina cuando un usuario puede ver el panel de evetos.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $evento
     * @return mixed
     */
    public function verPanelEventos(User $user)
    {
        $tieneEventos = false;

        if($user->esAdministrador()){
            $tieneEventos = true;
        }
        else{
            $organizador = Colaborador::find($user->id);
            $colaborador = Organizador::find($user->id);
            if( isset($organizador) || isset($colaborador) ){
                $tieneEventos = true;
            } 
        }

        return $tieneEventos;
    }

    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $evento
     * @return mixed
     */
    public function view(User $user, Evento $evento)
    {
        //
    }

    /**
     * Determine whether the user can create events.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $evento
     * @return mixed
     */
    public function update(User $user, Evento $evento)
    {
        //
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $evento
     * @return mixed
     */
    public function delete(User $user, Evento $evento)
    {
        //
    }

    /**
     * Determine whether the user can addSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function agregarSubevento(User $user, Evento $event)
    {
        return $user->id === $event->organizador_id;
    }

    /**
     * Determine whether the user can addSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function actualizarSubevento(User $user, Evento $event)
    {
        return $user->id === $event->organizador_id;
    }

    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function eliminarSubevento(User $user, Evento $event)
    {
        return $user->id === $event->organizador_id;
    }

    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function verPanelSubeventos(User $user, Evento $event)
    {
        $verPanel = false;

        //Es el organizador del evento
        if($user->id === $event->organizador_id){
            $verPanel = true;
        }
        else{
            $esColaborador = $event->subeventos()
                ->with('colaboradores')
                ->get()
                ->pluck('colaboradores')
                ->collapse()
                ->unique('id')
                ->contains('id', $user->id);

            if($esColaborador){
                $verPanel = true;
            }
        }

        return $verPanel;
    }

    /**
     * Determine si el usuario tiene opcion de listar Asistentes al evento.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function listarAsistentes(User $user, Evento $event)
    {
        $ret = false;
        //Si es el organizador del evento
        if($user->id === $event->organizador_id)
        {
            $ret = true;            
        }else{
            //Si es colaborador del evento
            $colaborador = Colaborador::find($user->id);
            $subs = $colaborador->subeventos()->get();
            foreach ($subs as $sub) {
                if ($sub->evento_id  === $event->id ) $ret = true;
            }
        }
        return $ret;

    }
    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function verAsistente(User $user, Evento $event, Asistente $assistant)
    {
        $ret = false;
        if($user->id === $event->organizador_id || $user->id === $assistant->id){
            $ret = true;
        }else{
            //Si es colaborador del evento
            $colaborador = Colaborador::find($user->id);
            $subs = $colaborador->subeventos()->get();
            foreach ($subs as $sub) {
                if ($sub->evento_id  === $event->id ) $ret = true;
            }
        }

        return $ret;
    }


    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function actualizarAsistente(User $user, Evento $event)
    {

        //return $user->id === $event->organizador_id;
        $ret = false;
        if($user->id === $event->organizador_id ){
            $ret = true;
        }else{
            //Si es colaborador del evento
            $colaborador = Colaborador::find($user->id);
            $subs = $colaborador->subeventos()->get();
            foreach ($subs as $sub) {
                if ($sub->evento_id  === $event->id ) $ret = true;
            }
        }

        return $ret;
    }

    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function eliminarAsistente(User $user, Evento $event, Asistente $assistant)
    {
        if($user->id === $event->organizador_id || $user->id === $assistant->id){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can deleteSubevent the event.
     *
     * @param  \App\User  $user
     * @param  \App\Evento  $event
     * @return mixed
     */
    public function enviarMensajeAsistente(User $user, Evento $event)
    {
        return $user->id === $event->organizador_id;
    }
}
