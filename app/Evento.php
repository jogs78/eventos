<?php

namespace App;
use App\Asistente;
use App\Subevento;
use App\Organizador;
use App\Precio;
use App\Transformers\EventTransformer;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
	const EVENTO_NO_VISIBLE = '0';
	const EVENTO_VISIBLE = '1';

	//Atributo que indica que tranformador le pertenece al modelo
	public $transformer = EventTransformer::class;
	
	//Campos rellenables
	protected $fillable = [
		'nombre',
		'descripcion',
		'url_imagen',
		'url_mas_info',
		'fecha_inicio',
		'fecha_finalizacion',
		'visible', 
		//'precio_inscripcion', 
		'detalles_pago',
		'max_subeventos_elegibles',
		//'limite_asistentes',
		'organizador_id',
	];

    protected $casts = [
        'organizador_id' => 'int',
    ];
    
    /**
     * Comprueba si un evento es visible.
     *
     * @return boolean
     */
	public function esVisible(){
		return $this->attributes['visible'] == Evento::EVENTO_VISIBLE;
	}

    /**
     * Comprueba si el evento tiene subevento(s).
     *
     * @return boolean
     */
	public function tieneSubeventos(){
		return $this->subeventos()->count() > 0 ? true : false;
	}

    /**
     * Comprueba si el evento tenía precio de inscripción despúes de ser actulizado a null.
     *
     * @return boolean
     */
	public function eraPagoPorEvento(){

		return isset($this->original['precio_inscripcion']);
	}

    /**
     * Comprueba si el evento es de la modalidad pago por evento.
     *
     * @return boolean
     */
    public function esPagoPorEvento(){

        return $this->precios->count() > 0 ? true : false;
    }



    /**
     * Relación uno a uno con Organizador.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function organizador(){
		return $this->belongsTo(Organizador::class);
	}

    /**
     * Relación uno a muchos con Subevento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function subeventos(){
		return $this->hasMany(Subevento::class);
	}

    /**
     * Relación muchos a muchos con Asistente.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function asistentes(){
		return $this->belongsToMany(Asistente::class)->withPivot('estatus', 'url_baucher')->withTimestamps();
	}
/*
    public function setFechaInicioAttribute($valor){
    	
    	$date = date_create_from_format("d/m/Y", $valor);
        
        $this->attributes['fecha_inicio'] = date_format($date, "Y-m-d");
    }

    public function getFechaInicioAttribute($valor){

    	$date = date_create_from_format("Y-m-d", $valor);

        return date_format($date,"d/m/Y");
    }

    public function setFechaFinalizacionAttribute($valor){
    	
    	$date = date_create_from_format("d/m/Y", $valor);

        $this->attributes['fecha_finalizacion'] = date_format($date, "Y-m-d");
    }

    public function getFechaFinalizacionAttribute($valor){
    	$date = date_create_from_format("Y-m-d", $valor);

        return date_format($date,"d/m/Y");
    }
*/

    /**
     * Obtiene los precios de inscripcion del evento.
     *
     * 
     */
    public function precios(){
        return $this->morphMany(Precio::class, 'precio');
    }

}
