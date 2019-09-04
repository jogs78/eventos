<?php

namespace App;
use App\Evento;
use App\Subevento;
//use App\Scopes\AssistantScope;
use App\Transformers\AssistantTransformer;

class Asistente extends User
{
	const ASISTENTE_REGISTRADO = "0";
	const ASISTENTE_EN_VERIFICACION = "1";
	const ASISTENTE_VERIFICADO = "2";

    //Atributo que indica que tranformador le pertenece al modelo
    public $transformer = AssistantTransformer::class;
    
    /*
    protected static function boot(){
        parent::boot();

        static::addGlobalScope(new AssistantScope);
    }
    */

    /**
     * Relación muchos a muchos con Evento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
    public function eventos(){
    	return $this->belongsToMany(Evento::class)->withPivot('evento_id', 'estatus', 'url_baucher')->withTimestamps();
    }

    /**
     * Relación muchos a muchos con Subevento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
    public function subeventos(){
    	return $this->belongsToMany(Subevento::class)->withPivot('evento_id','subevento_id','estatus', 'url_baucher')->withTimestamps();
    }

}
