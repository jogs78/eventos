<?php

namespace App\Providers;

use App\User;
use App\Evento;
use App\Asistente;
use App\Subevento;
use App\Colaborador;
use App\Organizador;
use App\Policies\UserPolicy;
use App\Policies\EventPolicy;
use App\Policies\SubeventPolicy;
use App\Policies\AssistantPolicy;
use App\Policies\OrganizerPolicy;
use App\Policies\CollaboratorPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    /*    
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];
    */
    protected $policies = [
        Asistente::class => AssistantPolicy::class,
        Colaborador::class => CollaboratorPolicy::class,
        Evento::class => EventPolicy::class,
        Organizador::class => OrganizerPolicy::class,
        Subevento::class => SubeventPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin-action', function ($user) {
            return $user->esAdministrador();
        });

        Gate::define('staff-action', function ($user) {
            return $user->esStaff();
        });

    }
}
