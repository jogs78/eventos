<?php

namespace App\Http\Controllers\Organizador;

use App\Organizador;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class OrganizerController extends ApiController
{
    public function __construct(){
        $this->middleware('can:view,organizer')->only('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();

        return $this->showAll(Organizador::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Organizador $organizer)
    {
        return $this->showOne($organizer);
    }

}
