<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'InicioController@index');


//Auth::routes();
// Authentication Routes...
Route::post('login', 'Auth\LoginController@login')->name('entrar');
Route::post('logout', 'Auth\LoginController@logout')->name('salir');


// Password Reset Routes...
Route::get('contraseña/restablecer', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('contraseña/restablecer/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('contraseña/restablecer', 'Auth\ResetPasswordController@reset');




/**
 * Users
 */
Route::get('usuarios', 'User\UserController@panel')->name('usuarios');
Route::get('registrarse', 'User\UserController@registrar')->name('usuarios.registro');
Route::get('miperfil', 'User\UserController@perfil')->name('usuarios.miperfil');
Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');
Route::name('resend')->get('users/{user}/resend', 'User\UserController@resend');
Route::name('mail')->post('users/{user}/mail', 'User\UserController@sendMail');
Route::get('staff', 'User\UserController@staff')->name('usuarios.staff');
Route::resource('users', 'User\UserController', ['except' => ['create','edit']]);


/**
 * Assistants
 */
//Asistente
//Route::get('asistente/{asistente}/inscripciones', 'Asistente\AssistantsController@mostrarPanelInscripciones')->name('asistente.inscripciones');
Route::get('inscripciones', 'Asistente\AssistantController@mostrarPanelInscripciones')->name('asistente.inscripciones');
Route::get('assistants/{assistant}/events/{event}/datosDeposito', 'Asistente\AssistantEventController@descargarDatosDeposito')->name('datosDepositoEvento');
Route::get('assistants/{assistant}/subevents/{subevent}/datosDeposito', 'Asistente\AssistantSubeventController@descargarDatosDeposito')->name('datosDepositoSubevento');

//Route::resource('assistants', 'Asistente\AssistantsController', ['only' => ['index','show']]);
Route::resource('assistants.events', 'Asistente\AssistantEventController', ['except' => ['create', 'edit', 'destroy'] ]);
Route::resource('assistants.subevents', 'Asistente\AssistantSubeventController', ['except' => ['create', 'edit', 'destroy'] ]);

/**
 * Collaborators
 */
Route::resource('collaborators', 'Colaborador\CollaboratorController', ['only' => ['index','show']]);
Route::resource('collaborators.subevents', 'Colaborador\CollaboratorSubeventController', ['only' => ['index','show']]);
Route::resource('collaborators.events', 'Colaborador\CollaboratorEventController', ['only' => ['index', 'show']]);

/**
 * Events
 */

//Vista eventos admin/staff
Route::get('eventos', 'Evento\EventController@mostrarPanelEventos')->name('eventos');
//Vista evento/subevento staff
Route::get('evento/{evento}/subeventos', 'Evento\EventSubeventController@mostrarPanelSubeventos')->name('evento.subeventos');
//Vista asistentes
Route::get('evento/{event}/asistentes', 'Evento\EventAssistantController@mostrarPanelAsistentes')->name('asistentes.evento');
//Correo a asistente
Route::post('events/{event}/assistants/{assistant}/mail', 'Evento\EventAssistantController@sendMail')->name('evento.asistente.mail');

Route::get('events/{event}/assistants/toPDF', 'Evento\EventAssistantController@toPDF')->name("asistentesEventoPDF");

Route::resource('events', 'Evento\EventController', ['except' => ['create','edit']]);

Route::resource('events.subevents', 'Evento\EventSubeventController', ['except' => ['create','edit']]);
Route::resource('events.assistants', 'Evento\EventAssistantController', ['except' => ['create','edit', 'store']]);
Route::resource('events.organizers', 'Evento\EventOrganizerController', ['only' => ['index']]);

Route::resource('events.prices', 'Evento\EventPriceController', ['except' => ['create', 'edit']]);

/**
 * Organizers
 */
Route::resource('organizers', 'Organizador\OrganizerController', ['only' => ['index','show']]);
Route::resource('organizers.events', 'Organizador\OrganizerEventController', ['only' => ['index','show']]);

/**
 * Subevents
 */
//Vista asistentes
Route::get('evento/{event}/subevento/{subevent}/asistentes', 'Subevento\SubeventAssistantController@mostrarPanelAsistentes')->name('asistentes.subevento');
//Lista asistentes en pdf
Route::get('subevents/{subevent}/assistants/toPDF', 'Subevento\SubeventAssistantController@toPDF')->name("asistentesSubeventoPDF");
//Correo a asistente
Route::post('subevents/{subevent}/assistants/{assistant}/mail', 'Subevento\SubeventAssistantController@sendMail')->name('subevento.asistente.mail');

Route::resource('subevents', 'Subevento\SubeventController', ['only' => ['index','show']]);
Route::resource('subevents.assistants', 'Subevento\SubeventAssistantController', ['except' => ['create','edit', 'store']]);
Route::resource('subevents.collaborators', 'Subevento\SubeventCollaboratorController', ['except' => ['create','edit', 'store']]);

Route::resource('subevents.prices', 'Subevento\SubeventPriceController', ['except' => ['create', 'edit']]);

/*
Rutas de Contacto
*/
Route::get('contacto', 'ContactoController@contacto')->name('contacto.formulario');
Route::post('contacto', 'ContactoController@enviarMensaje')->name('contacto.mensaje');

/*
Acerca de
*/
Route::get('acerca-de', function(){ return view('acerca-de'); })->name('acerca-de');
