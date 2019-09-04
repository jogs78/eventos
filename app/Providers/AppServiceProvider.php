<?php

namespace App\Providers;

use App\User;
use App\Evento;
use App\Precio;
use App\Asistente;
use App\Subevento;
use App\Colaborador;
use App\Organizador;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        User::created(function ($user){
            retry(5, function () use ($user){ 
                Mail::to($user)->send(new UserCreated($user));
            }, 100);
        });

        User::updated(function ($user){
            if($user->isDirty('email')){
                retry(5, function () use ($user){ 
                    Mail::to($user)->send(new UserMailChanged($user));
                }, 100);
            }
        });

        Evento::updated(function ($event){
            //Se eliminan los precios del evento.
            /* Problema, se eliminan los precios del evento cuando se crea un subevento porque en el escuchador update del subevento se actualiza el evento.
            $event->precios->each(function($precio,$key){
                $precio->delete();
            });
            */

        });

        Evento::deleted(function ($event){
            //Se eliminan los subeventos del evento.
            $event->subeventos->each(function($subevent, $key){
                if(isset($subevent->url_imagen)){
                    Storage::disk('images')->delete($subevent->url_imagen);
                }

            });

            //Se eliminan los precios del evento.
            $event->precios->each(function($precio,$key){
                $precio->delete();
            });
        });

        Subevento::updated(function ($subevent){
            //Se eliminan los precios del subevento.
            /*
            $subevent->precios->each(function($precio,$key){
                $precio->delete();
            });
            */

        });


        Subevento::created(function ($subevent){
            $event = Evento::find($subevent->evento_id);
            /*
                Cuando se crea un subevento, al evento que le corresponde se pone en estado visible.
            */
            if(!$event->esVisible()){
                $event->visible = Evento::EVENTO_VISIBLE;
                $event->save();
            }
        });

        Subevento::deleted(function ($subevent){
            $event = Evento::find($subevent->evento_id);
            /*
                Cuando el evento se queda sin eventos, se pone en estado no visible.
            */
            if(!$event->tieneSubeventos()){
                $event->visible = Evento::EVENTO_NO_VISIBLE;
                $event->save();
            }

            //Se eliminan los precios del subevento.
            $subevent->precios->each(function($precio,$key){
                $precio->delete();
            });
        });

        Precio::created(function ($price){
            /*
                Al asignar el primer precio a un evento se eliminan los precios de sus subeventos.
            */
            if($price->precio_type == Evento::class){
                $event = Evento::find($price->precio_id);
                if($event->precios->count() == 1){
                    $event->subeventos->each(function($subevent, $key){
                        $subevent->precios->each(function($precioSubevento, $keySubevento){
                            $precioSubevento->delete();
                        });
                    });
                }
            }
        });

        Precio::deleted(function ($price){
            /*
                Si el numero de precios del evento o subevento llega a 0, el campo información de pago se vuelve null.
            */
            $instance = $price->precio_type::find($price->precio_id);

            if($instance->precios->count() == 0){
                $instance->detalles_pago = null;
                $instance->save();
            }

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
