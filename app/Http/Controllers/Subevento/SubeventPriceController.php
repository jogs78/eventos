<?php

namespace App\Http\Controllers\Subevento;

use App\Evento;
use App\Precio;
use App\Subevento;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubeventPriceController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Subevent Price Controller
    |--------------------------------------------------------------------------
    |
    | Este controlador es responsable de manejar las operaciones de los precios
    | de los subeventos.
    |
    */

    public function __construct(){
        $this->middleware('can:agregarPrecio,subevent')->only(['store']);
        $this->middleware('can:quitarPrecio,subevent')->only(['destroy']);
        $this->middleware('can:actualizarPrecio,subevent')->only(['update']);
    }

    /**
     * Muestra todos los precios del subevento.
     *
     * @param  \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function index(Subevento $subevent)
    {
        $precios = $subevent->precios;

        return $this->showAll($precios);
    }


    /**
     * Almacena un nuevo precio para el subevento especificado.
     *
     * @param  \App\Subevento $subevent
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Subevento $subevent)
    {
        $reglas = [
            'descripcion' => 'required|min:1',
            'precio' => 'required|numeric|min:0',
        ];

        $this->validate($request, $reglas);

        $event= Evento::find($subevent->evento_id);

        /*
            Si se intenta asignar precio al subevento cuando es pago por evento.
        */
        if($event->esPagoPorEvento()){
            return $this->errorResponse("El subevento no puede tener precio de inscripción porque es de la modalidad pago por evento.", 409);
        }

        $campos = $request->only([
            'descripcion',
            'precio',
        ]);

        $campos['precio_id'] = $subevent->id;
        $campos['precio_type'] = Subevento::class;

        $precio = Precio::create($campos);

        return $this->showOne($precio, 201);
    }

    /**
     * Muestra el precio especificado del subevento especificado.
     *
     * @param  \App\Subevento $subevento
     * @param  \App\Precio $price
     * @return \Illuminate\Http\Response
     */
    public function show(Subevento $subevent, Precio $price)
    {
        $this->verificarPrecio($subevent, $price);

        return $this->showOne($price);
    }

    /**
     * Actualiza el precio especificado del subevento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subevento $subevent
     * @param  \App\Precio $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subevento $subevent, Precio $price)
    {
        $this->verificarPrecio($subevent, $price);

        $reglas = [
            'descripcion' => 'min:1',
            'precio' => 'numeric|min:0',
        ];

        $this->validate($request, $reglas);

        $campos = $request->only([
            'descripcion',
            'precio',
        ]);

        $price->fill($campos);

        $price->save();

        return $this->showOne($price, 201);
    }

    /**
     * Elimina el precio especificado del subevento especificado.
     *
     * @param  \App\Subevento $subevent
     * @param  \App\Precio  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subevento $subevent, Precio $price)
    {
        $this->verificarPrecio($subevent, $price);
        
        /*
            Si se intenta eliminar el último precio del subevento y ya tiene asistentes. 
        */
        if($subevent->asistentes->count() > 0  && $subevent->precios->count() == 1){
            return $this->errorResponse("No se puede dejar sin ningún precio de inscripción a subeventos que ya tienen asistentes.", 409);
        }

        $price->delete();

        return $this->showOne($price, 201);
    }

    /**
     * Comprueba si el precio especificado es precio del subevento especificado.
     *
     * @param  \App\Subevento  $subevent
     * @param  \App\Precio  $price
     * @return \Illuminate\Http\Response
     */
    protected function verificarPrecio(Subevento $subevent, Precio $price){

        $price = $subevent->precios()->find($price->id);

        if(!isset($price)){
            throw new HttpException(404, 'El precio especificado no es un precio de este subevento');
        }
    }
}
