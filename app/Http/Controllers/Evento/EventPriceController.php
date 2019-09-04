<?php

namespace App\Http\Controllers\Evento;

use App\Evento;
use App\Precio;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventPriceController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Event Price Controller
    |--------------------------------------------------------------------------
    |
    | Este controlador es responsable de manejar las operaciones de los precios
    | de los eventos.
    |
    */

    /**
     * Muestra todos los precios del evento.
     *
     * @param  \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function index(Evento $event)
    {
        $precios = $event->precios;

        return $this->showAll($precios);
    }


    /**
     * Almacena un nuevo precio para el evento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Evento $event)
    {
        $this->allowedAdminAction();

        $reglas = [
            'descripcion' => 'required|min:1',
            'precio' => 'required|numeric|min:0',
        ];

        $this->validate($request, $reglas);

        $campos = $request->only([
            'descripcion',
            'precio',
        ]);
        
        /*
            Si ya hay asistentes y se está intentando asignar el primer precio al evento.
        */
        if($event->asistentes->count() > 0 && $event->precios->count() == 0){
            return $this->errorResponse("No se puede asignar precios a eventos que no tienen ningun precio de inscripción y que ya tienen asistentes.", 409);
        }

        $campos['precio_id'] = $event->id;
        $campos['precio_type'] = Evento::class;

        $precio = Precio::create($campos);

        return $this->showOne($precio, 201);
    }

    /**
     * Muestra el precio especificado del evento especificado.
     *
     * @param  \App\Evento  $evento
     * @param  \App\Precio $price
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $event, Precio $price)
    {
        $this->verificarPrecio($event, $price);

        return $this->showOne($price);
    }

    /**
     * Actualiza el precio especificado del evento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento $event
     * @param  \App\Precio $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $event, Precio $price)
    {
        $this->allowedAdminAction();
        
        $this->verificarPrecio($event, $price);

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
     * Elimina el precio especificado del evento especificado.
     *
     * @param  \App\Evento  $evento
     * @param  \App\Precio  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $event, Precio $price)
    {
        $this->allowedAdminAction();

        $this->verificarPrecio($event, $price);
        
        if($event->asistentes->count() > 0  && $event->precios->count() == 1){
            return $this->errorResponse("No se puede dejar sin ningún precio de inscripción a eventos que ya tienen asistentes.", 409);
        }

        $price->delete();

        return $this->showOne($price, 201);
    }

    /**
     * Comprueba si el precio especificado es precio del evento especificado.
     *
     * @param  \App\Evento  $evento
     * @param  \App\Precio  $price
     * @return \Illuminate\Http\Response
     */
    protected function verificarPrecio(Evento $event, Precio $price){

        $price = $event->precios()->find($price->id);

        if(!isset($price)){
            throw new HttpException(404, 'El precio especificado no es un precio de este evento');
        }
    }
}
