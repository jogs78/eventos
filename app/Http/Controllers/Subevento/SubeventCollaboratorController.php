<?php

namespace App\Http\Controllers\Subevento;

use App\User;
use App\Subevento;
use App\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ApiController;
use App\Transformers\CollaboratorTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubeventCollaboratorController extends ApiController
{

    public function __construct(){
        $this->middleware('can:agregarColaborador,subevent')->only('update');
        $this->middleware('can:quitarColaborador,subevent')->only('destroy');
    }
    
    /**
     * Muestra la lista de todos los colaboradores del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @return \Illuminate\Http\Response
     */
    public function index(Subevento $subevent)
    {
        $colaboradores = $subevent->colaboradores;

        return $this->showAll($colaboradores);
    }

    /**
     * Muestra el colaborador especificado del subevento especificado.
     *
     * @param \App\Subevento $subevent
     * @param \App\Colaborador $collaborator
     * @return \Illuminate\Http\Response
     */
    public function show(Subevento $subevent, Colaborador $collaborator)
    {
        $collaborator = $this->verificarColaborador($subevent, $collaborator);

        return $this->showOne($collaborator);
    }

    /**
     * Agrega o actualiza el usuario especificado como colaborador del subevento especificado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subevento  $subevent
     * @param  \App\User $collaborator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subevento $subevent, User $collaborator)
    {
        //$request['colaborador_id'] = $collaborator->id;
        $rules = [
            'tipo' => 'required|in:' . Colaborador::COLABORADOR_RESPONSABLE . ',' . Colaborador::COLABORADOR_AYUDANTE,
            /*'colaborador_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('tipo', User::USUARIO_STAFF);
                })
            ]*/
        ];

        $this->validate($request, $rules);

        if($collaborator->tipo != User::USUARIO_STAFF){
            return $this->errorResponse('Solo pueden ser colaboradores los usuarios del staff.',422);
        }

        if($subevent->evento->organizador_id == $collaborator->id){
            return $this->errorResponse('El colaborador proporcionado es el organizador del evento.',422);
        }
        
        $colaborador_responsable = $subevent->colaboradores()->where('colaborador_subevento.tipo', Colaborador::COLABORADOR_RESPONSABLE)->first();

        if($request->tipo == Colaborador::COLABORADOR_RESPONSABLE && isset($colaborador_responsable)){
            if($colaborador_responsable->id != $collaborator->id){
                $subevent->colaboradores()->updateExistingPivot($colaborador_responsable->id, [
                    'tipo' =>  Colaborador::COLABORADOR_AYUDANTE
                ]);
            }
        }   
        
        
        
        /*$colaboradores = $subevent->colaboradores;

        if($colaboradores->count() > 0 && $colaboradores->pluck('pivot')->contains('tipo', Colaborador::COLABORADOR_RESPONSABLE)){
            $colaborador_responsable = $colaboradores->where('pivot.tipo', Colaborador::COLABORADOR_RESPONSABLE)->first();

            if($request->tipo == Colaborador::COLABORADOR_RESPONSABLE && $collaborator->id != $colaborador_responsable->id){
                return $this->errorResponse('Solo puede haber un colaborador responsable del subevento.',422);
            }
        }*/
        

        $subevent->colaboradores()->syncWithoutDetaching([$collaborator->id => [
            'tipo' => $request['tipo']
        ]]);

        return $this->showAll($subevent->colaboradores, 201);
    }

    /**
     * Elimina al colaborador especificado del subevento especificado.
     *
     * @param  \App\Subevento  $subevent
     * @param  \App\Colaborador  $collaborator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subevento $subevent, Colaborador $collaborator)
    {
        $this->verificarColaborador($subevent, $collaborator);

        $subevent->colaboradores()->detach([$collaborator->id]);

        return $this->showAll($subevent->colaboradores);
    }

    /**
     * Comprueba si el colaborador especificado es colaborador del subevento especificado.
     *
     * @param  \App\Subevento  $subevent
     * @param  \App\Colaborador  $collaborator
     * @return \Symfony\Component\HttpKernel\Exception\HttpException | \App\Colaborador $collaborator
     */
    protected function verificarColaborador(Subevento $subevent, Colaborador $collaborator){
        
        $collaborator = $subevent->colaboradores()->find($collaborator->id);

        if(!isset($collaborator)){
            throw new HttpException(404, 'El colaborador especificado no es colaborador del subevento especificado.');
        }

        return $collaborator;

    }

}
