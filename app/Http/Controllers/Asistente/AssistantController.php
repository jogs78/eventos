<?php

namespace App\Http\Controllers\Asistente;

use App\Asistente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class AssistantController extends ApiController
{
    /**
     * Crea una nueva instancia de controller.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('can:verInscripciones,App\Asistente')->only('mostrarPanelInscripciones');
    }

	/*
    public function mostrarPanelInscripciones(Asistente $asistente){
        
        return view('asistentes.inscripciones')->with(compact('asistente'));
    }
    */

    /**
     * Muestra el panel de inscripciones.
     *
     * @return \Illuminate\Http\Response
     */
    public function mostrarPanelInscripciones(){
    
        return view('asistentes.inscripciones');
    }
}
