<?php

namespace App\Http\Controllers\Subevento;

use PDF;
use App\Evento;
use App\Asistente;
use App\Subevento;
use App\Mail\CustomMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Transformers\AssistantTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubeventAssistantController extends ApiController
{
    public function __construct(){
        $this->middleware('can:verAsistente,subevent,assistant')->only('show');
        $this->middleware('can:listarAsistentes,subevent')->only('index', 'mostrarPanelAsistentes', 'toPDF');
        $this->middleware('can:actualizarAsistente,subevent')->only('update');
        $this->middleware('can:eliminarAsistente,subevent,assistant')->only('destroy');
        $this->middleware('can:enviarMensajeAsistente,subevent')->only(['sendMail']);
    }

    /**
     * Muestra la lista los asistentes del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function index(Subevento $subevent)
    {
        $asistentes = $subevent->asistentes;

        if(request()->has('toPDF')){
            return $this->showAllToPDF($asistentes, 'asistentes', 'pdf.asistentes', 'asistentesSubevento', compact('subevent'));
        } 

        return $this->showAll($asistentes);
    }


    /**
     * Muestra el asistente especificado del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @param \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function show(Subevento $subevent, Asistente $assistant)
    {
        $assistant = $this->verificarAsistente($subevent, $assistant);

        return $this->showOne($assistant);
    }

    /**
     * Actualiza el estado a verificado del asistente especificado del subevento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Subevento $subevent
     * @param \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subevento $subevent, Asistente $assistant)
    {
        $assistant = $this->verificarAsistente($subevent, $assistant);

        $datos= [
            'estatus' => Asistente::ASISTENTE_VERIFICADO,
            'evento_id' => $subevent->evento_id,
        ];

        if($assistant->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('El asistente ya ha sido aprobado', 422);
        }

        $subevent->asistentes()->syncWithoutDetaching([$assistant->id => $datos]);

        return $this->showOne($subevent->asistentes()->find($assistant->id), 201);
    }

    /**
     * Elimina el asistente especificado del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @param \App\Asistente $assistant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subevento $subevent, Asistente $assistant)
    {
        $assistant = $this->verificarAsistente($subevent, $assistant);
        /*
        if($assistant->pivot->estatus == Asistente::ASISTENTE_VERIFICADO){
            return $this->errorResponse('No se puede eliminar a un asistente que ya ha sido aprobado', 422);
        }
        */

        if(date_create() > date_create($subevent->fecha)){
            return $this->errorResponse('El subevento ya no está disponible.',409);
        }

        $event = Evento::find($subevent->evento_id);
        $asistenteEvento = $event->asistentes()->find($assistant->id);
        $numSubeventos = $assistant->subeventos()->get()->where('evento_id', $subevent->evento_id)->count();

        return DB::transaction(function() use($subevent, $event, $assistant, $numSubeventos, $asistenteEvento) {
    
            if(isset($assistant->pivot->url_baucher)){
                Storage::disk('images')->delete($assistant->pivot->url_baucher);
            }

            if(isset($subevent->limite_asistentes)){
                $subevent->limite_asistentes += 1;
                $subevent->save();
            }

            if($numSubeventos == 1 && !isset($event->precio_inscripcion)){
                if(isset($asistenteEvento->pivot->url_baucher)){
                    Storage::disk('images')->delete($asistenteEvento->pivot->url_baucher);
                }
                $event->asistentes()->detach([$assistant->id]);
            }

            $subevent->asistentes()->detach([$assistant->id]);

            return $this->showOne($assistant);
        });
        
    }

    /**
     * Verifica que el asistente especificado sea asistente del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @param \App\Asistente $assistant
     * @return \Symfony\Component\HttpKernel\Exception\HttpException | \App\Asistente
     */
    protected function verificarAsistente(Subevento $subevent, Asistente $assistant){
        
        $assistant = $subevent->asistentes()->find($assistant->id);

        if(!isset($assistant)){
            throw new HttpException(404, 'El asistente especificado no es un asistente de este subevento');
        }
       
        return $assistant;
    
    }

    /**
     * Verifica que el evento especificado sea el padre del subevento especificado.
     *
     * @param \App\Evento $event
     * @param \App\Subevento $subevent
     * @return \Symfony\Component\HttpKernel\Exception\HttpException | \App\Subevento
     */
    protected function verificarEvento(Evento $event, Subevento $subevent){
        
        $subevent = $event->subeventos()->find($subevent->id);

        if(!isset($subevent)){
            throw new HttpException(404);
        }
       
        return $subevent;
    
    }

    /**
     * Muestra la vista del panel de asistestes del evento/subevento especificados.
     *
     * @param \App\Evento $event
     * @param \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function mostrarPanelAsistentes(Evento $event, Subevento $subevent){

        $this->verificarEvento($event, $subevent);

        return view('asistentes.subevento')->with(['evento'=>$event, 'subevento' => $subevent]);
    
    }

    /**
     * Genera el archivo PDF con la lista de todos los asistentes aprobados del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function toPDF(Subevento $subevent){

        $subevento = $subevent;
        
        $asistentes = $subevento->asistentes()->get()->where('pivot.estatus', Asistente::ASISTENTE_VERIFICADO);

        $asistentes = $asistentes->isEmpty() ? null : $asistentes;

        $pdf = PDF::loadView('pdf.asistentes1', compact('subevento','asistentes'));
        
        return $pdf->download(str_slug("asistentes-".$subevent->nombre."-".now()).".pdf");
    }

    /**
     * Envia un mensaje al correo del asistente especificado del subevento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request, Subevento $subevent, Asistente $assistant)
    {
        $this->validate($request, [
            'mensaje' => 'required|min:1'
        ]);

        $this->verificarAsistente($subevent, $assistant);

        retry(5, function () use ($assistant, $subevent, $request){ 
            Mail::to($assistant)->send(new CustomMail($assistant, "Acerca del subevento ".$subevent->nombre, $request->mensaje));
        }, 100);

        return $this->showMessage("El correo ha sido enviado a $assistant->nombre $assistant->apellido_paterno $assistant->apellido_materno ($assistant->email)");

    }
}
