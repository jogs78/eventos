<?php

namespace App;
use App\Subevento;
use App\Scopes\CollaboratorScope;
use App\Transformers\CollaboratorTransformer;

class Colaborador extends User
{
	const COLABORADOR_RESPONSABLE = "R";
	const COLABORADOR_AYUDANTE = "A";

	//Atributo que indica que tranformador le pertenece al modelo
	public $transformer = CollaboratorTransformer::class;
    
    /**
     * Constructor de la clase.
     *
     * @return void
     */
	protected static function boot(){
		parent::boot();
		//Agregando el scope perteneciente al modelo
		static::addGlobalScope(new CollaboratorScope);
	}

    /**
     * Relación muchos a muchos con Subevento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
    public function subeventos(){
    	return $this->belongsToMany(Subevento::class)->withPivot('tipo');
    }
}
