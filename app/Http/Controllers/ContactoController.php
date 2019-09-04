<?php

namespace App\Http\Controllers;

use App\Mail\CustomMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;

class ContactoController extends ApiController
{
    //
	public function contacto(){

		return view('contacto');
	}

	public function enviarMensaje(Request $request){
		$rules = [
			'correo' => 'required|email',
			'asunto' => 'required|min:3',
			'mensaje' => 'required|min:5'
		];

		$this->validate($request, $rules);

    	$from = [
    		'address' => $request->correo,
    		'name' => 'Contacto Eventos ITTG'
    	];

        retry(5, function () use ($request, $from){ 

            Mail::to(config("mail.from.address"))->send(new CustomMail(null, $request->asunto, $request->mensaje, $from));
        }, 100);

        return $this->showMessage("Hemos enviado tu mensaje. :)");
     
	}
}
