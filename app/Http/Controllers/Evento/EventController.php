<?php

namespace App\Http\Controllers\Evento;

use App\User;
use Validator;
use App\Evento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Transformers\EventTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class EventController extends ApiController
{
    /**
     * Crea una nueva instancia de controller
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();

        $this->middleware('transform.input:' . EventTransformer::class)->only(['store', 'update']);
        $this->middleware('can:verPanelEventos,App\Evento')->only(['mostrarPanelEventos']);
        
    }

    /**
     * Muestra la lista de todos los eventos
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventos = Evento::all();

        return $this->showAll($eventos);
    }


    /**
     * Almacena un nuevo evento
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->allowedAdminAction();

        $v = Validator::make($request->all(), [
            'nombre' => 'required|min:1',
            'descripcion' => 'required|min:1',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_finalizacion' => 'required|date_format:Y-m-d|after_or_equal:fecha_inicio',
            //'visible' => 'required|in:' . Evento::EVENTO_VISIBLE . ',' . Evento::EVENTO_NO_VISIBLE,
            'precio_inscripcion' => 'nullable|numeric|min:1',
            'url_mas_info' => 'nullable|url',
            'url_imagen' => 'nullable|image',
            'organizador_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('tipo', User::USUARIO_STAFF);
                })
            ]
        ]);

        $v->sometimes(['detalles_pago'], 'required|min:1', function ($input) use($request) {

            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->sometimes(['max_subeventos_elegibles'], 'required|numeric|min:1', function ($input) use($request) {

            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->validate();

        $campos = $request->only([
            'nombre',
            'descripcion',
            'url_imagen',
            'url_mas_info',
            'fecha_inicio',
            'fecha_finalizacion',
            'visible', 
            //'precio_inscripcion', 
            'detalles_pago',
            'max_subeventos_elegibles',
            'organizador_id'
        ]);

        /*
            Si el campo precio_inscripción está presente en la solicitud y es nulo, los campos detalles_pago y max_subeventos_elegibles son nulos. 
        */
        if($request->has('precio_inscripcion') && !isset($request->precio_inscripcion)){
            //$campos['precio_inscripcion'] = null;
            $campos['detalles_pago'] = null;
            $campos['max_subeventos_elegibles'] = null;
        }

        /*
            Si el campo url_image está presente en la solicitud, se almacena la imagen en el servidor y se guarda el nombre generado en la bd. 
        */
        if($request->hasFile('url_imagen')){
            $campos['url_imagen'] = $request->url_imagen->store('', 'images');
        }

        $campos['visible'] = Evento::EVENTO_NO_VISIBLE;

        $evento = Evento::create($campos);

        return $this->showOne($evento, 201);
    }

    /**
     * Muestra el evento especificado
     *
     * @param  \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $event)
    {

        return $this->showOne($event);
    }


    /**
     * Actualiza el evento especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $event)
    {
        $this->allowedAdminAction();

        $v = Validator::make($request->all(), [
            'nombre' => 'min:1',
            'descripcion' => 'min:1',
            'fecha_inicio' => 'date_format:Y-m-d',
            'fecha_finalizacion' => 'date_format:Y-m-d|after_or_equal:fecha_inicio',
            'visible' => 'in:' . Evento::EVENTO_VISIBLE . ',' . Evento::EVENTO_NO_VISIBLE,
            'precio_inscripcion' => 'nullable|numeric|min:1',
            'url_mas_info' => 'nullable|url',
            'url_imagen' => 'nullable|image',
            'organizador_id' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('tipo', User::USUARIO_STAFF);
                })
            ]
        ]);

        $v->sometimes(['detalles_pago'], 'min:1', function ($input) use($request) {

            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->sometimes(['max_subeventos_elegibles'], 'numeric|min:1', function ($input) use($request) {

            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->validate();

        /*
            Retornar error si ya hay asistentes y si está intentando cambiar el tipo de inscripción.
             De pago o gratis
            $event->esPagoPorEvento() && !isset($request->precio_inscripcion)
             De gratis a pago
            !$event->espagoPorEvento && isset($request->precio_inscripcion)
        */
        if($event->asistentes->count() > 0 && ( ($event->esPagoPorEvento() && !isset($request->precio_inscripcion)) || (!$event->espagoPorEvento() && isset($request->precio_inscripcion)) ) ){
            return $this->errorResponse("No se puede editar el tipo de inscripción a eventos que ya tienen asistentes.", 409);
        }

        $campos = $request->only([
            'nombre',
            'descripcion',
            'url_imagen',
            'url_mas_info',
            'fecha_inicio',
            'fecha_finalizacion',
            'visible', 
            //'precio_inscripcion', 
            'detalles_pago',
            'max_subeventos_elegibles',
            'organizador_id'
        ]);

        if($request->hasFile('url_imagen')){
            
            if(isset($event->url_imagen)){
                Storage::disk('images')->delete($event->url_imagen);
            }

            $campos['url_imagen'] = $request->url_imagen->store('', 'images');
        }

        //$campos = collect($campos);
        //El campo precio inscripcion está presente y no tiene valor asignado.
        if($request->has('precio_inscripcion') && !isset($request->precio_inscripcion)){
            //Se eliminan los precios del evento
            $event->precios->each(function($precio,$key){
                $precio->delete();
            });
            
            $campos['detalles_pago'] = null;
            $campos['max_subeventos_elegibles'] = null;
        }


        //$event->fill($campos->all());
        $event->fill($campos);
        
        /*
        if($event->isClean()){
            return $this->errorResponse('Se debe de especificar al menos un valor diferente para actualizar.', 422);
        }
        /*
        
        /*

        if(!$event->isDirty()){
            return $this->errorResponse('Se debe de especificar al menos un valor diferente para actualizar.', 422);            
        }
        */

        $event->save();

        return $this->showOne($event, 201);
    }

    /**
     * Elimina el evento especificado
     *
     * @param  \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $event)
    {   

        $this->allowedAdminAction();

        if($event->asistentes->count() > 0 ){
            return $this->errorResponse("No se puede eliminar un evento que ya tiene asistentes", 409);
        }

        if(isset($event->url_imagen)){
            Storage::disk('images')->delete($event->url_imagen);
        }
            
        $event->delete();

        return $this->showOne($event, 201);
    }

    public function mostrarPanelEventos()
    {   
        return view('eventos.panel');
    }

}
