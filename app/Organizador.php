<?php

namespace App;
use App\Evento;
use App\Scopes\OrganizerScope;
use App\Transformers\OrganizerTransformer;

class Organizador extends User
{
	//Atributo que indica que tranformador le pertenece al modelo
	public $transformer = OrganizerTransformer::class;
	
	protected static function boot(){
		parent::boot();
		//Agregando el scope perteneciente al modelo
		static::addGlobalScope(new OrganizerScope);
	}

    /**
     * Relación uno a muchos con Evento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
    public function eventos(){
    	return $this->hasMany(Evento::class);
    }
}
