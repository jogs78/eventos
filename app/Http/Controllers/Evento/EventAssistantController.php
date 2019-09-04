<?php

namespace App\Http\Controllers\Evento;

use PDF;
use App\User;
use App\Evento;
use App\Asistente;
use App\Mail\CustomMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Transformers\AssistantTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EventAssistantController extends ApiController
{
    public function __construct(){
        $this->middleware('can:listarAsistentes,event')->only(['index', 'mostrarPanelAsistentes', 'toPDF']);
        
        $this->middleware('can:verAsistente,event,assistant')->only(['show']);

        $this->middleware('can:actualizarAsistente,event')->only(['update']);
        
        $this->middleware('can:eliminarAsistente,event,assistant')->only(['destroy']);

        $this->middleware('can:enviarMensajeAsistente,event')->only(['sendMail']);

    }

    /**
     * Muestra los asistentes del evento especificado.
     *
     * @param  \App\Evento  $event
     * @return \App\Http\Controllers\ApiController
     */
    public function index(Evento $event)
    {
        $asistentes = $event->asistentes;

        if(request()->has('toPDF')){
            return $this->showAllToPDF($asistentes, 'asistentes', 'pdf.asistentes', 'asistentesEvento', compact('event'));
        } 

        return $this->showAll($asistentes);
    }

    /**
     * Muestra el asistente especificado del evento especificado.
     *
     * @param  \App\Evento  $event
     * @param  \App\Asistente  $assitant
     * @return \App\Http\Controllers\ApiController
     */
    public function show(Evento $event, Asistente $assistant)
    {
        $assistant = $this->verificarAsistente($event, $assistant);

        return $this->showOne($assistant);
    }


    /**
     * Actualiza el asistente especificado del evento especificado en el almacenamiento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Evento  $event
     * @param  \App\Asistente  $assitant
     * @return \App\Http\Controllers\ApiController
     */

    public function update(Request $request, Evento $event, Asistente $assistant)
    {

        $assistant = $this->verificarAsistente($event, $assistant);

        if($assistant->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('El asistente ya ha sido aprobado', 422);
        }

        $datos['estatus'] = Asistente::ASISTENTE_VERIFICADO;

        $event->asistentes()->syncWithoutDetaching([$assistant->id => $datos]);

        return $this->showOne($event->asistentes()->find($assistant->id), 201);
    }

    /**
     * Eliminar el asistente especificado del evento especificado en el almacenamiento.
     *
     * @param  \App\Evento  $event
     * @param  \App\Asistente  $assitant
     * @return \App\Http\Controllers\ApiController
     */
    public function destroy(Evento $event, Asistente $assistant)
    {
        $assistant = $this->verificarAsistente($event, $assistant);
        /*
        if($assistant->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('No se puede eliminar a un asistente que ya ha sido aprobado', 422);
        }
        */

        if(date_create() > date_create($event->fecha_finalizacion." 23:59:59")){
            return $this->errorResponse('El evento ya no está disponible.',409);
        }

        $subevents = $assistant->subeventos()->get()->where('evento_id', $event->id);

        return DB::transaction(function() use($event, $subevents, $assistant) {
            foreach ($subevents as $key => $subevent) {
                if(isset($subevent->pivot->url_baucher)){
                    Storage::disk('images')->delete($subevent->pivot->url_baucher);
                }

                if(isset($subevent->limite_asistentes)){
                    $subevent->limite_asistentes += 1;
                    $subevent->save();
                }

                $subevent->asistentes()->detach([$assistant->id]);
            }
            
            if(isset($assistant->pivot->url_baucher)){
                Storage::disk('images')->delete($assistant->pivot->url_baucher);
            }

            $event->asistentes()->detach([$assistant->id]);

            return $this->showOne($assistant);

        });
    }

    /**
     * Comprueba si el asistente especificado esta inscrito en el evento especificado.
     *
     * @param  \App\Evento  $event
     * @param  \App\Asistente  $assitant
     * @return \Symfony\Component\HttpKernel\Exception\HttpException | \App\Asistente
     */
    protected function verificarAsistente(Evento $event, Asistente $assistant){

        $assistant = $event->asistentes()->find($assistant->id);

        if(!isset($assistant)){
            throw new HttpException(404, 'El asistente especificado no es un asistente de este evento');
        }
       
        return $assistant;
    }


    public function mostrarPanelAsistentes(Evento $event){
    
        return view('asistentes.evento', ['evento' => $event]);

    }

    /**
     * Genera el archivo PDF con la lista de todos los asistentes aprobados del evento especificado.
     *
     * @param \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function toPDF(Evento $event){

        $evento = $event;

        $asistentes = $evento->asistentes()->get()->where('pivot.estatus', Asistente::ASISTENTE_VERIFICADO);
        
        $asistentes = $asistentes->isEmpty() ? null : $asistentes;
        
        $pdf = PDF::loadView('pdf.asistentes1', compact('evento','asistentes'));
        
        return $pdf->download(str_slug("asistentes-".$event->nombre."-".now()).".pdf");
    }

    /**
     * Envia un mensaje al correo del asistente especificado del evento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Evento $event
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request, Evento $event, Asistente $assistant)
    {
        $this->validate($request, [
            'mensaje' => 'required|min:1'
        ]);

        $this->verificarAsistente($event, $assistant);

        retry(5, function () use ($assistant, $event, $request){ 
            Mail::to($assistant)->send(new CustomMail($assistant, "Acerca del evento ".$event->nombre, $request->mensaje));
        }, 100);

        return $this->showMessage("El correo ha sido enviado a $assistant->nombre $assistant->apellido_paterno $assistant->apellido_materno ($assistant->email)");

    }

}
