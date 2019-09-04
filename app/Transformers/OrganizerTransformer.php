<?php

namespace App\Transformers;

use App\Organizador;
use League\Fractal\TransformerAbstract;

class OrganizerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Organizador $organizer)
    {
        return [
            'clave' => (int) $organizer->id,
            'nombre' => (string) $organizer->nombre,
            'apellidoPaterno' => (string) $organizer->apellido_paterno,
            'apellidoMaterno' => (string) $organizer->apellido_materno,
            'sexo' => (string) $organizer->sexo,
            'telefono' => isset($organizer->telefono) ? (string) $organizer->telefono : null,
            'correo' => (string) $organizer->email,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'clave' => 'id',
            'nombre' => 'nombre',
            'apellidoPaterno' => 'apellido_paterno',
            'apellidoMaterno' => 'apellido_materno',
            'sexo' => 'sexo',
            'telefono' => 'telefono',
            'correo' => 'email',
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
            'telefono' => 'telefono',
            'email' => 'correo',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
