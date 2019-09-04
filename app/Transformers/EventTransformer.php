<?php

namespace App\Transformers;

use App\Evento;
use App\Asistente;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Evento $event)
    {
        $transform = [
            'clave' => (int)$event->id,
            'titulo' => (string)$event->nombre,
            'detalles' => (string)$event->descripcion,
            'imagen' => isset($event->url_imagen) ? url("img/{$event->url_imagen}") : url("img/eventos-ittg.png"),
            'masInformacion' => isset($event->url_mas_info) ? url($event->url_mas_info) : null,
            'fechaInicio' => (string)$event->fecha_inicio,
            'fechaFin' => (string)$event->fecha_finalizacion,
            'visible' => (int)$event->visible,
            'precioInscripcion' => $event->precios->count() > 0 ? true : null,
            'informacionPago' => isset($event->detalles_pago) ? (string)$event->detalles_pago : null,
            'subeventosElegibles' =>  isset($event->max_subeventos_elegibles) ? (int)$event->max_subeventos_elegibles : null,
            'organizador' => (int)$event->organizador_id,
            'numeroAsistentes' => (int) $event->asistentes->count(),
        ];

        if(isset($event->pivot->asistente_id)){
            $transform['fechaRegistro'] = (string) $event->pivot->created_at; 
            $transform['estado'] = (int) $event->pivot->estatus;
            if($event->esPagoPorEvento()){
                $transform['referencia'] = (string) $event->id . (string) $event->pivot->asistente_id; 
            }
            else{
                $transform['referencia'] = null;
            }
            $transform['baucher'] = isset($event->pivot->url_baucher) ? url("img/{$event->pivot->url_baucher}") : null;
            $transform['subeventosElegidos'] = Asistente::find($event->pivot->asistente_id)->subeventos()->get()->where('evento_id', $event->id)->count();
        }

        return $transform;
    }

    public static function originalAttribute($index){
        $attributes = [
            'clave' => 'id',
            'titulo' => 'nombre',
            'detalles' => 'descripcion',
            //'imagen' => 'url_imagen',
            'masInformacion' => 'url_mas_info',
            'fechaInicio' => 'fecha_inicio',
            'fechaFin' => 'fecha_finalizacion',
            'visible' => 'visible',
            'precioInscripcion' => 'precio_inscripcion',
            'informacionPago' => 'detalles_pago',
            'subeventosElegibles' => 'max_subeventos_elegibles',
            'organizador' => 'organizador_id',
            'estado' => 'pivot.estatus',
            'fechaRegistro' => 'pivot.created_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'clave',
            'nombre' => 'titulo',
            'descripcion' => 'detalles',
            //'url_imagen' => 'imagen',
            'url_mas_info' => 'masInformacion',
            'fecha_inicio' => 'fechaInicio',
            'fecha_finalizacion' => 'fechaFin',
            'visible' => 'visible',
            'precio_inscripcion' => 'precioInscripcion',
            'detalles_pago' => 'informacionPago',
            'max_subeventos_elegibles' => 'subeventosElegibles',
            'organizador_id' => 'organizador',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
