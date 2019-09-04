<?php

namespace App\Http\Controllers\Colaborador;

use App\Subevento;
use App\Colaborador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CollaboratorController extends ApiController
{

    public function __construct(){
        $this->middleware('can:view,collaborator')->except(['index']);
    }

    /**
     * Muestra la lista de todos los colaboradores
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();
        
        $colaboradores = Colaborador::all();

        return $this->showAll($colaboradores);
    }

    /**
     * Muestra al colaborador especificado
     *
     * @param  \App\Colaborador  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show(Colaborador $collaborator)
    {
        /*
            public function show($id)
            $colaborador = Colaborador::has('subeventos')->findOrFail($id);
        */
        return $this->showOne($collaborator);
    }
}
