<?php

namespace App\Http\Controllers\Organizador;

use App\Evento;
use App\Organizador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OrganizerEventController extends ApiController
{
    public function __construct(){
        $this->middleware('can:view,organizer');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organizador $organizer)
    {
        $eventos = $organizer->eventos;

        return $this->showAll($eventos);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Organizador  $organizador
     * @return \Illuminate\Http\Response
     */
    public function show(Organizador $organizer, Evento $event)
    {
        $this->verificarEvento($organizer, $event);

        return $this->showOne($event);

    }

    protected function verificarEvento(Organizador $organizer, Evento $event){
        
        if($event->organizador->id !== $organizer->id){
            throw new HttpException(404, 'No es organizador del evento especificado.');
        }

    }

}
