<?php
use App\User;
use App\Evento;
use App\Subevento;
use App\Colaborador;
use App\Organizador;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        // $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Evento::truncate();
        Subevento::truncate();
        DB::table('colaborador_subevento')->truncate();
        DB::table('asistente_evento')->truncate();
        DB::table('asistente_subevento')->truncate();

        User::flushEventListeners();
        Evento::flushEventListeners();
        Subevento::flushEventListeners();

        $cantidadUsuarios = 40;
        $cantidadEventos = 10;
        $cantidadSubeventos = 20;

        factory(User::class, $cantidadUsuarios)->create();
        factory(Evento::class, $cantidadEventos)->create();
        factory(Subevento::class, $cantidadSubeventos)->create()->each(
            function($subevento){
                $organizador = $subevento->evento->organizador_id;
                $colaboradores = User::where('tipo', User::USUARIO_STAFF)->get()->except(['id', $organizador])->random(mt_rand(1, 4))->pluck('id');
                $subevento->colaboradores()->attach($colaboradores->first(), ['tipo' => Colaborador::COLABORADOR_RESPONSABLE]);
            }
        );
        */
        /*
            Usuario administrador con correo administrador
        */
        $administrador = [
            'nombre' => 'Administrador',
            'apellido_paterno' => 'adminAP',
            'apellido_materno' => 'adminAM',
            'sexo' => User::USUARIO_MASCULINO,
            'telefono' => null, 
            'email' => 'administrador@eventos.ittg.mx', 
            'password' => bcrypt('administrador'),
            'tipo' => User::USUARIO_ADMINISTRADOR,
            'verificado' => User::USUARIO_VERIFICADO,
        ];

        User::create($administrador);
        /*
        $staff = User::create([
            'nombre' => 'Staff',
            'apellido_paterno' => 'staffAP',
            'apellido_materno' => 'staffAM',
            'sexo' => User::USUARIO_MASCULINO,
            'telefono' => null, 
            'email' => 'staff@eventos.ittg.mx', 
            'password' => bcrypt('staff'),
            'tipo' => User::USUARIO_STAFF,
            'verificado' => User::USUARIO_VERIFICADO,

        ]);   

        $evento = Evento::create([
            'nombre' => 'MICAI 2018',
            'descripcion' => 'Descripción micai 2018',
            //'url_imagen' => null,
            //'url_mas_info' => null,
            'fecha_inicio' => '2018-08-12',
            'fecha_finalizacion' => '2018-08-15',
            //'visible' => Evento::EVENTO_NO_VISIBLE, 
            //'precio_inscripcion' => 150, 
            //'detalles_pago' => 'BANAMEX 120332',
            //'max_subeventos_elegibles' => 2,
            'organizador_id' => $staff->id,
        ]);
        /*
        $subevento = Subevento::create([
            'nombre' => 'Taller de IOS',
            'descripcion' => 'Descripción taller de ios',
            'url_imagen' => 'cbR2OG7rtFAzqnaZQwHTp5MAZny9U8b1ygCPyTDD.jpeg',
            'fecha' => '2018-06-13 11:10:00',
            'lugar' => 'ITTG Edificio D1-10', 
            'precio_inscripcion' => null, 
            'detalles_pago' => null,
            'limite_asistentes' => 50,
            'evento_id' => $evento->id,
        ]);
        */
    }
}
