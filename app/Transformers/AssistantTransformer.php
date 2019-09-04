<?php

namespace App\Transformers;

use App\Evento;
use App\Asistente;
use App\Subevento;
use League\Fractal\TransformerAbstract;

class AssistantTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Asistente $assistant)
    {
        return [
            'clave' => (int) $assistant->id,
            'nombre' => (string) $assistant->nombre,
            'apellidoPaterno' => (string) $assistant->apellido_paterno,
            'apellidoMaterno' => (string) $assistant->apellido_materno,
            'sexo' => (string) $assistant->sexo,
            'ocupacion' => isset($assistant->ocupacion) ? (string) $assistant->ocupacion : null,
            'instituto-dependencia' => isset($assistant->procedencia) ? (string) $assistant->procedencia : null,
            'telefono' => isset($assistant->telefono) ? (string) $assistant->telefono : null,
            'correo' => (string) $assistant->email,
            'estado' => isset($assistant->pivot->estatus) ? (int) $assistant->pivot->estatus : null,
            'baucher' => isset($assistant->pivot->url_baucher) ? url("img/{$assistant->pivot->url_baucher}") : null,
            'referencia' => (function() use ($assistant){

                            if(isset($assistant->pivot->subevento_id) && Evento::find($assistant->pivot->evento_id)->esPagoPorEvento()){

                                return null;
                            }

                            //Cambiar comprobación en subevento
                            if(Evento::find($assistant->pivot->evento_id)->esPagoPorEvento() || ( isset($assistant->pivot->subevento_id) && Subevento::find($assistant->pivot->subevento_id)->esPagoPorSubevento() ) ){
                                $referencia = (string) $assistant->pivot->evento_id;

                                if(isset($assistant->pivot->subevento_id)){
                                    $referencia .= (string) $assistant->pivot->subevento_id;
                                }

                                $referencia .= (string) $assistant->id;

                                return $referencia;
                            }

                            return null;

                        })(),
            'fechaRegistro' => (string) $assistant->pivot->created_at,

 /*           'estado' => (function() use ($assistant){
                               return $assistant->;
                        })(),
*/
            'links' => [
                [
                    'rel' => 'assistant.events',
                    'href' => route('assistants.events.index', $assistant->id),
                ],
                [
                    'rel' => 'assistant.subevents',
                    'href' => route('assistants.subevents.index', $assistant->id),
                ],
            ],
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'clave' => 'id',
            'nombre' => 'nombre',
            'apellidoPaterno' => 'apellido_paterno',
            'apellidoMaterno' => 'apellido_materno',
            'sexo' => 'sexo',
            'ocupacion' => 'ocupacion',
            'instituto-dependencia' => 'procedencia',
            'telefono' => 'telefono',
            'correo' => 'email',
            'estado' => 'pivot.estatus',
            'baucher' => 'pivot.url_baucher',
            'fechaRegistro' => 'pivot.created_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'clave',
            'nombre' => 'nombre',
            'apellido_paterno' => 'apellidoPaterno',
            'apellido_materno' => 'apellidoMaterno',
            'sexo' => 'sexo',
            'sexo' => 'sexo',
            'ocupacion' => 'ocupacion',
            'procedencia' => 'instituto-dependencia',
            'telefono' => 'telefono',
            'email' => 'correo',
            'pivot.estatus' => 'estado',
            'pivot.url_baucher' => 'baucher',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
