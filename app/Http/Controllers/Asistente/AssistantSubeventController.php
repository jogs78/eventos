<?php

namespace App\Http\Controllers\Asistente;

use PDF;
use App\Evento;
use App\Asistente;
use App\Subevento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AssistantSubeventController extends ApiController
{
    /**
     * Crea una nueva instancia de controller.
     *
     * @return void
     */
    public function __construct(){
        $this->middleware('can:view,assistant');
    }

    /**
     * Muestra la lista de subeventos del asistente especificado
     *
     * @param \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function index(Asistente $assistant)
    {
        $subeventos = $assistant->subeventos;

        return $this->showAll($subeventos); 
    }

    /**
     * Registra al asistente especificado en un subevento
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Asistente $assistant)
    {
        $rules = [
            'subevento_id' => 'required|integer|exists:subeventos,id'
        ];

        $this->validate($request, $rules);

        if(!$assistant->esVerificado()){
            return $this->errorResponse('Antes de poder inscribirte al subevento, primero tienes que verificar tu cuenta.',409);
        }

        $subevent = Subevento::find($request->subevento_id);
        $event = Evento::find($subevent->evento_id);
        $asistenteEvento = $event->asistentes()->find($assistant->id);

        if(date_create() > date_create($subevent->fecha)){
            return $this->errorResponse('El subevento ya fue llevado a cabo.',409);
        }

        //Verificar si el evento tiene cupos disponibles
        if(isset($subevent->limite_asistentes) && $subevent->limite_asistentes == 0){

            return $this->errorResponse( "El subevento no tiene cupos disponibles" , 409); 
        }

        //Verificar que no sea el organizador del evento
        if($assistant->id == $event->organizador_id){
            return $this->errorResponse( "El organizador del evento no puede inscribirse en los subeventos del evento" , 409); 
        }

        //Verificar que no sea colaborador del subevento
        $colaborador = $subevent->colaboradores()->find($assistant->id);

        if(isset($colaborador)){
            return $this->errorResponse( "Un colaborador del subevento no puede inscribirse al subevento" , 409); 
        }


        /* Comprobación registro en el evento */
        //Si el candidato a asistente está registrado en el evento
        if(isset($asistenteEvento)){
            //No está aprobado
            if($asistenteEvento->pivot->estatus != Asistente::ASISTENTE_VERIFICADO){
                return $this->errorResponse('Su inscripción en el evento aún no está aprobada.', 409);
            }
        }
        //Tratar de registrarlo en el evento
        else{
            //Si es pago por evento
            if($event->esPagoPorEvento()){
                return $this->errorResponse('No está inscrito en el evento.', 409);
            }

            //Registrarlo en el evento
            $event->asistentes()->syncWithoutDetaching([$assistant->id => [
                'estatus' => Asistente::ASISTENTE_VERIFICADO,         
                'url_baucher' => null,
            ]]);
        }

        /* Fin Comprobación registro en el evento*/


        $datos = [
            'estatus' => Asistente::ASISTENTE_REGISTRADO,         
            'url_baucher' => null,
            'evento_id' => $event->id,
        ];

        //Verificar que el asistente no esté registrado de lo contrario retornar error
        $estaRegistrado = $subevent->asistentes()->find($assistant->id);

        if(isset($estaRegistrado)){
            return $this->errorResponse('Ya se encuentra registrado en el subevento especificado.',422);
        }

        //Si es pago por evento
        if($event->esPagoPorEvento()){
            $num_subeventos_elegidos = $assistant->subeventos()->get()->where('evento_id', $event->id)->count();
            //Si asistente al evento ha alcanzado el máximo de subeventos elegibles
            if($num_subeventos_elegidos == $event->max_subeventos_elegibles){
                return $this->errorResponse( "El asistente ya ha seleccionado el máximo de subeventos permitidos" , 409);
            }
            //Establece estatus a verificado en el subevento por ser pago por evento
            $datos['estatus'] = Asistente::ASISTENTE_VERIFICADO;
        }

        //Si el subevento no tiene precio de inscripcion
        if(!$subevent->esPagoPorSubevento()){
            $datos['estatus'] = Asistente::ASISTENTE_VERIFICADO;   
        }

        //Si el subevento tiene limite de asistentes, restarlo en el subevento
        if(isset($subevent->limite_asistentes)){

            return DB::transaction(function() use($assistant, $subevent, $datos) {
                $subevent->limite_asistentes -= 1;
                $subevent->save();

                $subevent->asistentes()->syncWithoutDetaching([$assistant->id => $datos]);

                return $this->showOne($assistant->subeventos()->find($subevent->id)); 
            });
        }
        //Agregar el asistente en el subevento
        $subevent->asistentes()->syncWithoutDetaching([$assistant->id => $datos]);

        //return $this->showAll($assistant->subeventos); 
        return $this->showOne($assistant->subeventos()->find($subevent->id));
    }

    /**
     * Muestra el subevento especificado del asistente especificado
     *
     * @param  \App\Asistente  $assistatn
     * @param  \App\Subevento  $subevent
     * @return \Illuminate\Http\Response
     */
    public function show(Asistente $assistant, Subevento $subevent)
    {
        $subevent = $this->verificarSubevento($assistant, $subevent);

        return $this->showOne($subevent);
    }

     /**
     * Actualiza al asistente en el subevento especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente  $assistant
     * @param  \App\Subevento  $subevent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asistente $assistant, Subevento $subevent)
    {
        $rules = [
            'url_baucher' => 'required|image',
        ];

        $this->validate($request, $rules);

        $subevent = $this->verificarSubevento($assistant, $subevent);

        $datos = [
            'estatus' => Asistente::ASISTENTE_EN_VERIFICACION,
            'evento_id' => $subevent->evento_id,
        ];

        if(date_create() > date_create($subevent->fecha)){
            return $this->errorResponse('El subevento ya no está disponible.',409);
        }

        if($subevent->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('Su inscripción ya ha sido aprobada.', 422);
        }

        
        if(isset($subevent->pivot->url_baucher)){
            Storage::disk('images')->delete($subevent->pivot->url_baucher);
        }

        $datos['url_baucher'] = $request->url_baucher->store('', 'images');
        
        //$subevent->asistentes()->syncWithoutDetaching([$assistant->id => $datos]);
        $subevent->asistentes()->updateExistingPivot($assistant->id, $datos);

        //return $this->showAll($assistant->subeventos);
        return $this->showOne($assistant->subeventos()->find($subevent->id));
    }

    /**
     * Comprueba si el asistente especificado está inscrito en el subevento especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente  $assistant
     * @param  \App\Subevento  $subevent
     * @return Symfony\Component\HttpKernel\Exception\HttpException | \App\Subevento
     */
    protected function verificarSubevento(Asistente $assistant, Subevento $subevent){

        $subevent =  $assistant->subeventos()->find($subevent->id);

        if(!isset($subevent)){
            throw new HttpException(404, 'No está inscrito al evento especificado');
        }
       
        return $subevent;
    }

    /**
     * Genera los datos de depósito del asistente especificado en el subevento especificado.
     *
     * @param  \App\Asistente  $assistant
     * @param  \App\Subevento  $subevent
     * @return PDF
     */
    public function descargarDatosDeposito(Asistente $assistant, Subevento $subevent)
    {
        $subevent = $this->verificarSubevento($assistant, $subevent);
        
        if(!$subevent->esPagoPorSubevento()){
            return $this->errorResponse('El subevento no posee datos de depósito',409);
        }

        $prices = $subevent->precios->sortByDesc('precio');

        $event = $assistant->eventos()->find($subevent->evento_id);

        $organizer = $event->organizador;

        $event = $this->transformData($event, $event->transformer)['data'];

        $event['fechaInicio'] =  $this->formatearFecha($event['fechaInicio']);
        
        $event['fechaFin'] = $this->formatearFecha($event['fechaFin']);
        
        $collaborators = $subevent->colaboradores;

        $collaborators = $collaborators->isEmpty() ? null : $collaborators;

        $subevent = $this->transformData($subevent, $subevent->transformer)['data'];

        $subevent['fechaRegistro'] = $this->formatearFecha($subevent['fechaRegistro']);

        $subevent['fechaHora'] = $this->formatearFecha($subevent['fechaHora']);

        $subevent['precioInscripcion'] = $prices;
        
        //$pdf = PDF::loadView('pdf.deposito', compact('event', 'subevent', 'collaborators', 'organizer' ,'assistant'));

        $pdf = PDF::loadView('pdf.deposito', compact('event','subevent', 'collaborators', 'organizer' ,'assistant'));

        return $pdf->download(str_slug("inscripcion-".$subevent['referencia']."-".$subevent['titulo']."-".now()).".pdf");
    }
    
    /**
     * Formatea la fecha recibida
     *
     * @param  string $fechaString
     * @return string
     */
    protected function formatearFecha($fechaString){

        $fecha = date_create($fechaString);

        $formato = is_bool(stristr($fechaString,":")) ? "d/m/Y" : "d/m/Y H:i:s";        
        
        return date_format($fecha, $formato);
    }

}
