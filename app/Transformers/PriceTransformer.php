<?php

namespace App\Transformers;

use App\Precio;
use League\Fractal\TransformerAbstract;

class PriceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Precio $price)
    {
        return [
            'clave' => (int) $price->id,
            'descripcion' => (string) $price->descripcion,
            'precio' => (double) $price->precio,
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'clave' => 'id',
            'descripcion' => 'descripcion',
            'precio' => 'precio',
        ];
        
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'clave',
            'descripcion' => 'descripcion',
            'precio' => 'precio',
        ];
        
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
