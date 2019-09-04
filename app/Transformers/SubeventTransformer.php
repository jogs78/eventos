<?php

namespace App\Transformers;

use App\Evento;
use App\Subevento;
use League\Fractal\TransformerAbstract;

class SubeventTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Subevento $subevent)
    {

        $transform = [
            'clave' => (int)$subevent->id,
            'titulo' => (string)$subevent->nombre,
            'detalles' => (string)$subevent->descripcion,
            'imagen' => isset($subevent->url_imagen) ? url("img/{$subevent->url_imagen}") : null,
            'fechaHora' => (string)$subevent->fecha,
            'lugar' => (string)$subevent->lugar,
            'precioInscripcion' => $subevent->esPagoPorSubevento() ? true : null,
            'informacionPago' => isset($subevent->detalles_pago) ? (string)$subevent->detalles_pago : null,
            'cuposDisponibles' => isset($subevent->limite_asistentes) ? (int)$subevent->limite_asistentes : null,
            'numeroAsistentes' => (int) $subevent->asistentes->count(),
            'evento' => (int)$subevent->evento_id,
        ];
        
        if(isset($subevent->pivot->asistente_id)){
            $transform['fechaRegistro'] = (string) $subevent->pivot->created_at;
            $transform['estado'] = (int) $subevent->pivot->estatus;

            $event = Evento::find($subevent->evento_id);
            if(!$event->esPagoPorEvento() && $subevent->esPagoPorSubevento()){
                $transform['referencia'] = (string) $subevent->evento_id . (string) $subevent->id . (string) $subevent->pivot->asistente_id; 
            }
            else{
                $transform['referencia'] = null;
            }

            $transform['baucher'] = isset($subevent->pivot->url_baucher) ? url("img/{$subevent->pivot->url_baucher}") : null;
             
        }

        return $transform;
    }

    public static function originalAttribute($index){
        $attributes = [
            'clave' => 'id',
            'titulo' => 'nombre',
            'detalles' => 'descripcion',
            //'imagen' => 'url_imagen',
            'fechaHora' => 'fecha',
            'lugar' => 'lugar',
            'precioInscripcion' => 'precio_inscripcion',
            'informacionPago' => 'detalles_pago',
            'cuposDisponibles' => 'limite_asistentes',
            'evento' => 'evento_id',
            'subeventoGratuito' => 'subeventoGratuito',
            'sinLimiteDeAsistentes' => 'sinLimiteDeAsistentes',
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
            'fecha' => 'fechaHora',
            'lugar' => 'lugar',
            'precio_inscripcion' => 'precioInscripcion',
            'detalles_pago' => 'informacionPago',
            'limite_asistentes' => 'cuposDisponibles',
            'evento_id' => 'evento',
        ];
        
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
