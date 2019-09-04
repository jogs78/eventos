<?php
use App\Colaborador;
use App\Evento;
use App\Organizador;
use App\Subevento;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'nombre' => $faker->firstName,
        'apellido_paterno' => $faker->lastName,
        'apellido_materno' => $faker->lastName,
        'sexo' => $faker->randomElement(['M','F']),
        'telefono' => $faker->tollFreePhoneNumber, 
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
 		'tipo' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_STAFF, User::USUARIO_ASISTENTE]),
        'verificado' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'token_verificacion' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarTokenVerificacion(),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Evento::class, function (Faker $faker) {
	$fecha_fin = $faker->date;
	$fecha_inicio = $faker->date ($max="$fecha_fin");
    return [
		'nombre' => $faker->word,
		'descripcion' => $faker->paragraph(1),
		'url_imagen' => $faker->randomElement(['1.png', '2.png', '3.png']),
		'url_mas_info'=> $faker->randomElement([null, $faker->url]),
		'fecha_inicio' => $fecha_inicio,
		'fecha_finalizacion' => $fecha_fin,
		'visible' => Evento::EVENTO_NO_VISIBLE,
		'precio_inscripcion' => $pago_evento = $faker->randomElement([null, $faker->randomFloat($nbMaxDecimals = NULL, $min = 1, $max = NULL)]), 
		'detalles_pago' => isset($pago_evento) ? $faker->paragraph(1) : null ,
		'max_subeventos_elegibles' => isset($pago_evento) ? $faker->numberBetween(1,5) : null ,
		'organizador_id' => User::where('tipo', User::USUARIO_STAFF)->get()->random()->id,
    ];
});

$factory->define(Subevento::class, function (Faker $faker) {
	$evento = Evento::all()->random();
	
	if(!$evento->esVisible()){
		$evento->visible = Evento::EVENTO_VISIBLE;
		$evento->save();
	}

    return [
		'nombre' => $faker->word,
		'descripcion' => $faker->paragraph(1),
		'url_imagen' => $faker->randomElement(['1.png', '2.png', '3.png']),
		'fecha' => $faker->dateTime,
		'lugar' => $faker->address,
		'precio_inscripcion' => $precio_inscripcion = isset($evento->precio_inscripcion) ? null : $faker->randomFloat($nbMaxDecimals = NULL, $min = 1, $max = NULL), 
		'detalles_pago' => isset($precio_inscripcion) ? $faker->paragraph(1) : null,
		'limite_asistentes' => $faker->randomElement([$faker->numberBetween(1,30), null]),
		'evento_id' => $evento->id,
    ];
});

