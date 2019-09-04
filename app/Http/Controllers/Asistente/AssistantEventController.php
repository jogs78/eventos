<?php

namespace App\Http\Controllers\Asistente;

use PDF;
use App\Evento;
use App\Asistente;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AssistantEventController extends ApiController
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
     * Muestra la lista de eventos del asistente especificado
     *
     * @param \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function index(Asistente $assistant)
    {
        $eventos = $assistant->eventos;

        return $this->showAll($eventos); 
    }

    /**
     * Registra al asistente especificado en un evento
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Asistente $assistant)
    {
        $rules = [
            'evento_id' => 'required|integer|exists:eventos,id'
        ];

        $this->validate($request, $rules);

        $event = Evento::find($request->evento_id);

        if(!$assistant->esVerificado()){
            return $this->errorResponse('Antes de poder inscribirte al evento, primero tienes que verificar tu cuenta.',409);
        }

        /*
        if(date_modify(date_create(), "-1 days") > date_create($event->fecha_finalizacion)){
            return $this->errorResponse('El evento ya fue llevado a cabo.',409);
        }
        */
        if(date_create() > date_create($event->fecha_finalizacion." 23:59:59")){
            return $this->errorResponse('El evento ya fue llevado a cabo.',409);
        }

        /*Comprueba que el evento esté disponible*/
        if(!$event->esVisible()){
            return $this->errorResponse('El evento no está disponible.',409);
        }

        //Comprueba que no sea el organizador del evento
        if($event->organizador_id == $assistant->id){
            return $this->errorResponse('El organizador no puede inscribirse a su evento.',422);
        }
        
        //Comprueba si el asistente ya está registrado
        if($event->asistentes()->find($assistant->id)){
            return $this->errorResponse('Ya se encuentra registrado en el evento especificado.',422);
        }

        //Comprueba si el evento tiene más de un evento en caso de que el asistente sea un colaborador de algún subevento
        $numSubeventos = $event->subeventos()->count();
        $esColaborador = $event->subeventos()->with('colaboradores')
                ->get()
                ->pluck('colaboradores')
                ->collapse()
                ->unique('id')
                ->values()
                ->contains('id', $assistant->id);

        if($esColaborador && $numSubeventos <= 1){
            return $this->errorResponse('El evento sólo tiene un subevento, en el cual usted es colaborador.',409);
        }


        $estatus = Asistente::ASISTENTE_REGISTRADO;

        //Si no es pago por evento, entonces se registra el asistente con estado verificado
        if(!$event->esPagoPorEvento()){
            $estatus = Asistente::ASISTENTE_VERIFICADO;
        }

        $assistant->eventos()->syncWithoutDetaching([$request->evento_id => [
            'estatus' => $estatus
        ]]);

        return $this->showAll($assistant->eventos);
    }

    /**
     * Muestra el evento especificado del asistente especificado
     *
     * @param  \App\Asistente  $assistatn
     * @param  \App\Evento  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Asistente $assistant, Evento $event)
    {
        $event = $this->verificarEvento($assistant, $event);

        return $this->showOne($event);
    }


    /**
     * Actualiza al asistente en el evento especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente  $assistant
     * @param  \App\Evento  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asistente $assistant, Evento $event)
    {
        $rules = [
            'url_baucher' => 'required|image',
        ];

        $this->validate($request, $rules);

        $event = $this->verificarEvento($assistant, $event);

        $datos = [
            'estatus' => Asistente::ASISTENTE_EN_VERIFICACION,
        ];

        if(date_create() > date_create($event->fecha_finalizacion." 23:59:59")){
            return $this->errorResponse('El evento ya no está disponible.',409);
        }

        if($event->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('Su inscripción ya ha sido aprobada.', 422);
        }

  
        if(isset($event->pivot->url_baucher)){
            Storage::disk('images')->delete($event->pivot->url_baucher);
        }

        $datos['url_baucher'] = $request->url_baucher->store('', 'images');
        

        $assistant->eventos()->syncWithoutDetaching([$event->id => $datos]);

        //return $this->showAll($assistant->eventos);
        return $this->showOne($assistant->eventos()->find($event->id));
        
    }

    /**
     * Comprueba si el asistente especificado está inscrito en el evento especificado
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente  $assistant
     * @param  \App\Evento  $event
     * @return Symfony\Component\HttpKernel\Exception\HttpException | \App\Evento
     */
    protected function verificarEvento(Asistente $assistant, Evento $event){

        $event =  $assistant->eventos()->find($event->id);

        if(!isset($event)){
            throw new HttpException(404, 'No está inscrito al evento especificado');
        }
       
        return $event;
    }
    
    /**
     * Genera los datos de depósito del asistente especificado en el evento especificado.
     *
     * @param  \App\Asistente  $assistant
     * @param  \App\Evento  $event
     * @return PDF
     */

    public function descargarDatosDeposito(Asistente $assistant, Evento $event)
    {
        $event = $this->verificarEvento($assistant, $event);
        
        if(!$event->esPagoPorEvento()){
            return $this->errorResponse('El evento no posee datos de depósito',409);
        }

        $organizer = $event->organizador;

        $prices = $event->precios->sortByDesc('precio');

        $event = $this->transformData($event, $event->transformer)['data'];

        $event['fechaInicio'] =  $this->formatearFecha($event['fechaInicio']);
        
        $event['fechaFin'] = $this->formatearFecha($event['fechaFin']);

        $event['fechaRegistro'] = $this->formatearFecha($event['fechaRegistro']);

        $event['precioInscripcion'] = $prices;

        $pdf = PDF::loadView('pdf.deposito', compact('event','organizer','assistant'));
        
        return $pdf->download(str_slug("inscripcion-".$event['referencia']."-".$event['titulo']."-".now()).".pdf");
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

