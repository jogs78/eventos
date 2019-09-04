<?php

namespace App\Http\Controllers\Colaborador;

use App\Subevento;
use App\Colaborador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CollaboratorSubeventController extends ApiController
{
    public function __construct(){
        $this->middleware('can:view,collaborator');
    }

    /**
     * Muestra los subeventos del colaborador especificado
     *
     * @param \App\Colaborador $collaborator
     * @return \Illuminate\Http\Response
     */
    public function index(Colaborador $collaborator)
    {
        $subeventos = $collaborator->subeventos;

        return $this->showAll($subeventos);
    }

    /**
     * Muestra el subevento especificado del colaborador especificado
     *
     * @param  \App\Colaborador  $collaborator
     * @param  \App\Subevento  $subevent
     * @return \Illuminate\Http\Response
     */
    public function show(Colaborador $collaborator, Subevento $subevent)
    {
        $this->verificarSubevento($collaborator, $subevent);

        return $this->showOne($subevent);
    }

    /**
     * Comprueba si el colaborador especificado pertenece al subevento especificado.
     *
     * @param  \App\Colaborador  $collaborator
     * @param  \App\Subevento  $subevent
     */
    protected function verificarSubevento(Colaborador $collaborator, Subevento $subevent){

        $collaborator = $collaborator->subeventos()->find($subevent->id);

        if(!isset($collaborator)){
            throw new HttpException(404, 'No es colaborador del subevento especificado.');
        }

    }
    
}
