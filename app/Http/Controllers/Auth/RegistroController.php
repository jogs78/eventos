<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RegistroController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Muestra el formulario de registro de usuarios.
     *
     * @return /resources/views/usuarios/registro
     */
    public function mostrarFormularioRegistro(){

        return view('usuarios.registro');
    }
}
