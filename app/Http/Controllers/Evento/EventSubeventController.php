<?php

namespace App\Http\Controllers\Evento;

use Validator;
use App\Evento;
use App\Subevento;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use App\Transformers\SubeventTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventSubeventController extends ApiController
{
    public function __construct(){
        parent::__construct();

        $this->middleware('transform.input:' . SubeventTransformer::class)->only(['store', 'update']);
        $this->middleware('can:agregarSubevento,event')->only(['store']);
        $this->middleware('can:actualizarSubevento,event')->only(['update']);
        $this->middleware('can:eliminarSubevento,event')->only(['destroy']);
        $this->middleware('can:verPanelSubeventos,evento')->only(['mostrarPanelSubeventos']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Evento $event)
    {
        $subeventos = $event->subeventos()->with('colaboradores')->get();

        return $this->showAll($subeventos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Evento $event)
    {
        
        $v = Validator::make($request->all(), [
            'nombre' => 'required|min:1',
            'descripcion' => 'required|min:1',
            'fecha' => 'required|date_format:Y-m-d H:i:s|after_or_equal:'.$event->fecha_inicio.' 00:00:00|before_or_equal:'.$event->fecha_finalizacion.' 23:59:59',
            'lugar' => 'required|min:1',
            'limite_asistentes' => 'nullable|integer|min:0',           
            'precio_inscripcion' => 'nullable|numeric|min:1',
            'url_imagen' => 'nullable|image',
        ]);

        $v->sometimes(['detalles_pago'], 'required|min:1', function ($input) use ($request){
            //return $input->precio_inscripcion > 0;
            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->validate();

        $campos = $request->only([
            'nombre',
            'descripcion',
            'url_imagen',
            'fecha',
            'lugar', 
            //'precio_inscripcion', 
            'detalles_pago',
            'limite_asistentes',
        ]);

        if($request->hasFile('url_imagen')){
            $campos['url_imagen'] = $request->url_imagen->store('', 'images');
        }

        $campos['evento_id'] = $event->id;

        if($request->has('precio_inscripcion')){
            if($event->esPagoPorEvento() && isset($request->precio_inscripcion)){
                return $this->errorResponse("El subevento no puede tener precio de inscripción porque es de la modalidad pago por evento.", 409);
            }

            if(!isset($request->precio_inscripcion)){
                $campos['detalles_pago'] = null;
            }
        }

        $subevento = Subevento::create($campos);

        return $this->showOne($subevento, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Evento $event, Subevento $subevent)
    {
        $this->verificarSubevento($event, $subevent);
        return $this->showOne($subevent);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $event, Subevento $subevent)
    {
        $this->verificarSubevento($event, $subevent);

        $v = Validator::make($request->all(), [
            'nombre' => 'min:1',
            'descripcion' => 'min:1',
            'fecha' => 'date_format:Y-m-d H:i:s|after_or_equal:'.$event->fecha_inicio.' 00:00:00|before_or_equal:'.$event->fecha_finalizacion.' 23:59:59',
            'lugar' => 'min:1',
            'limite_asistentes' => 'nullable|integer|min:0',           
            'precio_inscripcion' => 'nullable|numeric|min:1',
            'url_imagen' => 'nullable|image',
        ]);

        $v->sometimes(['detalles_pago'], 'required|min:1', function ($input) use ($request){
            return $request->has('precio_inscripcion') && isset($request->precio_inscripcion);
        });

        $v->validate();

         $campos = $request->only([
            'nombre',
            'descripcion',
            'url_imagen',
            'fecha',
            'lugar',
            //'precio_inscripcion', 
            'detalles_pago',
            'limite_asistentes'
        ]);

        /*
            Retornar error si ya hay asistentes y si está intentando cambiar el tipo de inscripción.
             De pago o gratis
            $subevent->esPagoPorSubevento() && !isset($request->precio_inscripcion)
             De gratis a pago
            !$subevent->esPagoPorSubevento && isset($request->precio_inscripcion)
        */
        if($subevent->asistentes->count() > 0 && ( ($subevent->esPagoPorSubevento() && !isset($request->precio_inscripcion)) || (!$subevent->esPagoPorSubevento() && isset($request->precio_inscripcion)) ) ){
            return $this->errorResponse("No se puede editar el tipo de inscripción a subeventos que ya tienen asistentes.", 409);
        }

        if($request->has('precio_inscripcion')){
            if($event->esPagoPorEvento() && isset($request->precio_inscripcion)){
                return $this->errorResponse("El subevento no puede tener precio de inscripción porque es de la modalidad pago por evento.", 409);
            }

            if(!isset($request->precio_inscripcion)){
                //Se eliminan los precios del evento aún cuando no se haya actualizado ningun campo, ya que puede que los precios hayan cambiado.
                $subevent->precios->each(function($precio,$key){
                    $precio->delete();
                });
                
                $campos['detalles_pago'] = null;
            }
        }

        if($request->hasFile('url_imagen')){
            if(isset($subevent->url_imagen)){
                Storage::disk('images')->delete($subevent->url_imagen);
            }
            $campos['url_imagen'] = $request->url_imagen->store('', 'images');
        }

        $subevent->fill($campos);

        /*
        if($subevent->isClean()){
            return $this->errorResponse('Se debe de especificar al menos un valor diferente para actualizar.', 422);            
        }
        */
        
        $subevent->save();

        return $this->showOne($subevent, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evento $event, Subevento $subevent)
    {
        $this->verificarSubevento($event, $subevent);
        
        if($subevent->asistentes->count() > 0 ){
            return $this->errorResponse("No se puede eliminar un subevento que ya tiene asistentes", 409);
        }

        if(isset($subevent->url_imagen)){
            Storage::disk('images')->delete($subevent->url_imagen);
        }
        

        $subevent->delete();

        return $this->showOne($subevent);
    }

    protected function verificarSubevento(Evento $event, Subevento $subevent){

        if(!$event->subeventos()->find($subevent->id)){
            throw new HttpException(404, 'El subevento especificado no pertenece a este evento');
        }
    }

    public function mostrarPanelSubeventos(Evento $evento)
    {   
        return view('eventos.subeventos', compact('evento'));
    }
}
