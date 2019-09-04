<?php

namespace App\Transformers;

use App\Colaborador;
use League\Fractal\TransformerAbstract;

class CollaboratorTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Colaborador $collaborator)
    {
        $transform = [
            'clave' => (int) $collaborator->id,
            'nombre' => (string) $collaborator->nombre,
            'apellidoPaterno' => (string) $collaborator->apellido_paterno,
            'apellidoMaterno' => (string) $collaborator->apellido_materno,
            'sexo' => (string) $collaborator->sexo,
            'telefono' => isset($collaborator->telefono) ? (string) $collaborator->telefono : null,
            'correo' => (string) $collaborator->email,
        ];

        if(isset($collaborator->pivot->tipo)){
            $transform['tipo'] = (string) $collaborator->pivot->tipo;
        }

        $transform['links'] = [
            [
                'rel' => 'self',
                'href' => route('collaborators.show', $collaborator->id),
            ],
            [
                'rel' => 'collaborator.subevents',
                'href' => route('collaborators.subevents.index', $collaborator->id),
            ],
            [
                'rel' => 'user',
                'href' => route('users.show', $collaborator->id),
            ],
        ];

        return $transform;
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
            'tipo' => 'pivot.tipo',
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
            'pivot.tipo' => 'tipo',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
