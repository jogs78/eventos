<?php

namespace App;
use App\Evento;
use App\Precio;
use App\Asistente;
use App\Colaborador;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\SubeventTransformer;

class Subevento extends Model
{
	//Atributo que indica que tranformador le pertenece al modelo
	public $transformer = SubeventTransformer::class;
	
	//Campos rellenables
	protected $fillable = [
		'nombre',
		'descripcion',
		'url_imagen',
		'fecha',
		'lugar', 
		'precio_inscripcion', 
		'detalles_pago',
		'limite_asistentes',
		'evento_id',
	];

	protected $casts = [
	    'evento_id' => 'int',
	];

    /**
     * Comprueba si el subevento tenía precio de inscripción despúes de ser actulizado a null.
     *
     * @return boolean
     */
	public function eraSubeventoPagado(){
		return isset($this->original['precio_inscripcion']);
	}

    /**
     * Comprueba si el subevento tiene cuota de inscripción.
     *
     * @return boolean
     */
	public function esPagoPorSubevento(){
		return $this->precios->count() > 0 ? true : false;
	}


    /**
     * Relación muchos a uno con Evento.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function evento(){
		return $this->belongsTo(Evento::class);
	}

    /**
     * Relación muchos a muchos con Colaborador.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function colaboradores(){
		return $this->belongsToMany(Colaborador::class)->withPivot('tipo');
	}

    /**
     * Relación muchos a muchos con Asistente.
     *
     * @return \Illuminate\Database\Eloquent\Concerns\HasRelationships
     */
	public function asistentes(){
		return $this->belongsToMany(Asistente::class)->withPivot('evento_id','estatus', 'url_baucher')->withTimestamps();
	}

    /**
     * Obtiene los precios de inscripcion del subevento.
     *
     * 
     */
    public function precios(){
        return $this->morphMany(Precio::class, 'precio');
    }

}
