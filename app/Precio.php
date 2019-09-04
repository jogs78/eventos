<?php

namespace App;

use App\Transformers\PriceTransformer;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
	//Atributo que indica que tranformador le pertenece al modelo
	public $transformer = PriceTransformer::class;

	protected $fillable = [
		'descripcion',
		'precio',
		'precio_id',
		'precio_type',
	];

    /**
     * Get all of the owning commentable models.
     */
    public function precio()
    {
        return $this->morphTo();
    }

}
