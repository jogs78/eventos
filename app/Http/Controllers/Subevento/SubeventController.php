<?php

namespace App\Http\Controllers\Subevento;

use App\Subevento;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SubeventController extends ApiController
{
    /**
     * Muestra la lista de todos los subeventos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subeventos = Subevento::all();

        return $this->showAll($subeventos);
    }

    /**
     * Muestra el subevento especificado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subevento $subevent)
    {
        return $this->showOne($subevent);
    }


}
