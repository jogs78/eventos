<?php

namespace App\Http\Controllers\Colaborador;

use App\Evento;
use App\Colaborador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CollaboratorEventController extends ApiController
{
    public function __construct(){
        $this->middleware('can:view,collaborator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Colaborador $collaborator)
    {       
        $events = $collaborator->subeventos()
            ->with('evento')
            ->get()
            ->pluck('evento')
            ->unique('id')
            ->values();

        return $this->showAll($events);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Colaborador $collaborator, Evento $event)
    {
        $this->verificarEvento($collaborator, $event);

        return $this->showOne($event);
    }

    protected function verificarEvento(Colaborador $collaborator, Evento $event){
        $perteneAlEvento = $collaborator->subeventos()
            ->with('evento')
            ->get()
            ->pluck('evento')
            ->unique('id')
            ->contains('id', $event->id);

        if(!$perteneAlEvento){
            throw new HttpException(404, 'De los subeventos que es colaborador, ninguno de ellos pertenece al evento especificado.');
        }

    }

}
