<?php

namespace App\Http\Controllers\Evento;

use App\Evento;
use App\Organizador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class EventOrganizerController extends ApiController
{
    /**
     * Muestra al organizador del evento especificado
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Evento $event)
    {
        //$organizador = $event->organizador()->get();
        $organizador = Organizador::find($event->organizador_id);


        return $this->showOne($organizador);
    }
}
