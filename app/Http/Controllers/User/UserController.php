<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\CustomMail;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\ApiController;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends ApiController
{
    public function __construct(){
        //parent::__construct();
        $this->middleware('auth')->except(['store', 'verify', 'registrar']);
        $this->middleware('guest')->only(['registrar']);
        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:resendConfirmationMail,user')->only('resend');
    }
    
    /** Almacenamiento y respuestas

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->allowedAdminAction();
        
        $usuarios = User::all();

        return $this->showAll($usuarios);
    }

    /**
     * Muestra a todos los usarios staff
     *
     * @return \Illuminate\Http\Response
     */
    public function staff()
    {
        $this->allowedAdminStaffAction();
        //Validar si es un usuario administrador o staff quien hace la petición.
        $usuarios = User::where('tipo', User::USUARIO_STAFF)->get();

        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'nombre' => 'required|min:1|string',
            'apellido_paterno' => 'required|min:1|string',
            'apellido_materno' => 'required|min:1|string',
            'sexo' => 'required|in:' . User::USUARIO_MASCULINO . ',' . User::USUARIO_FEMENINO,
            'ocupacion' => 'nullable',
            'procedencia' => 'nullable',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
            'telefono' => 'nullable',
            'tipo' => 'sometimes|required|in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_STAFF . ',' . User::USUARIO_ASISTENTE,

        ];

        $this->validate($request, $reglas);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verificado'] = User::USUARIO_NO_VERIFICADO;
        $campos['token_verificacion'] = User::generarTokenVerificacion();
        
        $campos['tipo'] = User::USUARIO_ASISTENTE;


        if($request->has('tipo')){
            $this->allowedAdminAction();
            $campos['tipo'] = $request->tipo;
        }

        $usuario = User::create($campos);

        if(!$request->has('tipo')){
            Auth::login($usuario);
        }

        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'nombre' => 'min:1|string',
            'apellido_paterno' => 'min:1|string',
            'apellido_materno' => 'min:1|string',
            'sexo' => 'in:' . User::USUARIO_MASCULINO . ',' . User::USUARIO_FEMENINO,
            'ocupacion' => 'nullable',
            'procedencia' => 'nullable',
            'email' => [
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'min:5|confirmed',
            'telefono' => 'nullable',
            'tipo' => 'sometimes|required|in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_STAFF . ',' . User::USUARIO_ASISTENTE,

        ];

        $this->validate($request, $reglas);
        
        $campos = $request->only([
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'sexo',
            'ocupacion',
            'procedencia',
            'email',
            'password',
            'telefono',
            'tipo',
        ]);
        
        if($request->has('email') && $request->email != $user->email){
            $campos['verificado'] = User::USUARIO_NO_VERIFICADO;
            $campos['token_verificacion'] = User::generarTokenVerificacion();
        }

        if($request->has('tipo')){
            $this->allowedAdminAction();

            if(Auth::id() == $user->id){
                throw new AuthorizationException();
            }
            
            $campos['tipo'] = $request->tipo;
        }

        $user->fill($campos);

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe de especificar al menos un valor diferente para actualizar.', 422);            
        }

        $user->save();

        return $this->showOne($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->allowedAdminAction();

        if(Auth::id() == $user->id){
            throw new AuthorizationException();
        }

        $user->delete();

        return $this->showOne($user, 201);
    }

    public function verify($token)
    {
        $user = User::where('token_verificacion', $token)->firstOrFail();
        $user->verificado = User::USUARIO_VERIFICADO;
        $user->token_verificacion = null;
        $user->save();

        if(!request()->expectsJson()){
            if(!Auth::check()){
                Auth::login($user);
            }

            return redirect("/")->with([
                    'estado' => 'success', 
                    'mensaje' => 'Gracias por verificar tu cuenta.'
                ]);
        }
        
        return $this->showMessage('Usuario verificado correctamente');
    }

    public function resend(User $user)
    {
        if($user->esVerificado()){
            return $this->errorResponse("Este usuario ya ha sido verificado.", 409);
        }

        retry(5, function () use ($user){ 
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificación se ha reenviado.');

    }

    public function sendMail(Request $request, User $user)
    {
        $this->allowedAdminAction();

        $this->validate($request, [
            'asunto' => 'required|min:1',
            'mensaje' => 'required|min:1'
        ]);

        retry(5, function () use ($user, $request){ 
            Mail::to($user)->send(new CustomMail($user, $request->asunto, $request->mensaje));
        }, 100);

        return $this->showMessage("El correo ha sido enviado a $user->nombre $user->apellido_paterno $user->apellido_materno ($user->email)");

    }

    /** Vistas **/
    public function panel(){
        $this->allowedAdminAction();
        return view('usuarios.panel');
    }

    public function registrar(){
        return view('usuarios.registro');
    }

    public function perfil(){
        return view('usuarios.miperfil');
    }
}
