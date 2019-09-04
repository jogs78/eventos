<?php

namespace App;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;
    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';
    const USUARIO_ADMINISTRADOR = '0';
    const USUARIO_STAFF = '1';
    const USUARIO_ASISTENTE = '2';
    const USUARIO_MASCULINO = 'm';
    const USUARIO_FEMENINO = 'f';

    /**
     * Atributo que indica el transformador que le correponde al modelo.
     *
     * @var string
     */
    public $transformer = UserTransformer::class;

    /**
     * Atributo que indica la tabla de la base de datos que le correponde al modelo.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'ocupacion',
        'procedencia',
        'telefono', 
        'email', 
        'password',
        'tipo',
        'verificado',
        'token_verificacion',
    ];

    /**
     * Mutadores y Accesores 
     * Los métodos set hacen una conversión del dato recibido antes de la inserción en la BD. (Mutadores)
     * Los métodos get hacen una conversión del dato consultado en la BD. (Accesores)
     */

    /**
     * Convierte el valor de entrada del campo nombre a minúsculas.
     */
    public function setNombreAttribute($valor){
        $this->attributes['nombre'] = strtolower($valor);
    }

    /**
     * Convierte el valor consultado del campo nombre a mayúscula la primer letra.
     */
    public function getNombreAttribute($valor){
        return ucfirst($valor);
    }

    /**
     * Convierte el valor de entrada del campo apellido_paterno a minúsculas.
     */
    public function setApellidoPaternoAttribute($valor){
        $this->attributes['apellido_paterno'] = strtolower($valor);
    }

    /**
     * Convierte el valor consultado del campo apellido_paterno a mayúscula la primer letra.
     */
    public function getApellidoPaternoAttribute($valor){
        return ucfirst($valor);
    }

    /**
     * Convierte el valor de entrada del campo apellido_materno a minúsculas.
     */
    public function setApellidoMaternoAttribute($valor){
        $this->attributes['apellido_materno'] = strtolower($valor);
    }

    /**
     * Convierte el valor consultado del campo apellido_materno a mayúscula la primer letra.
     */
    public function getApellidoMaternoAttribute($valor){
        return ucfirst($valor);
    }

    /**
     * Convierte el valor de entrada del campo sexo a minúsculas.
     */
    public function setSexoAttribute($valor){
        $this->attributes['sexo'] = strtolower($valor);
    }  

    /**
     * Convierte el valor de entrada del campo email a minúsculas.
     */
    public function setEmailAttribute($valor){
        $this->attributes['email'] = strtolower($valor);
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'token_verificacion',
    ];

    /**
     * Comprueba si el usuario se encuentra verificado.
     *
     * @return boolean
     */
    public function esVerificado(){
        return $this->verificado == User::USUARIO_VERIFICADO;
    }

    /**
     * Comprueba si el usuario es administrador.
     *
     * @return boolean
     */
    public function esAdministrador(){
        return $this->tipo == User::USUARIO_ADMINISTRADOR;
    }

    /**
     * Comprueba si el usuario es del staff.
     *
     * @return boolean
     */
    public function esStaff(){
        return $this->tipo == User::USUARIO_STAFF;
    }

    /**
     * Comprueba si el usuario es de tipo general.
     *
     * @return boolean
     */
    public function esAsistente(){
        return $this->tipo == User::USUARIO_ASISTENTE;
    }

    /**
     * Genera el token de verificación para el usuario.
     *
     * @return string
     */
    public static function generarTokenVerificacion(){
        return str_random(40);
    }

    /**
     * Sobrescribiendo el método para el envio de correo para el restablecimiento de contraseña.
     *
     */
    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }
    
}
