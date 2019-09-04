<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'clave' => (int) $user->id,
            'nombre' => (string) $user->nombre,
            'apellidoPaterno' => (string) $user->apellido_paterno,
            'apellidoMaterno' => (string) $user->apellido_materno,
            'sexo' => (string) $user->sexo,
            'ocupacion' => isset($user->ocupacion) ? (string) $user->ocupacion : null,
            'instituto-dependencia' => isset($user->procedencia) ? (string) $user->procedencia : null,
            'telefono' => isset($user->telefono) ? (string) $user->telefono : null,
            'correo' => (string) $user->email,
            'tipo' => (int) $user->tipo,
            'verificado' => (int) $user->verificado, 
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
            'tipo' => 'tipo',
            'verificado' => 'verificado',
            'contrasenia' => 'password',
            'contrasenia_confirmacion' => 'password_confirmation',

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
            'ocupacion' => 'ocupacion',
            'procedencia' => 'instituto-dependencia',
            'telefono' => 'telefono',
            'email' => 'correo',
            'tipo' => 'tipo',
            'verificado' => 'verificado',
            'password' => 'contrasenia',
            'password_confirmation' => 'contrasenia_confirmacion' 
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
