<?php
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('sexo');
            $table->string('ocupacion')->nullable();
            $table->string('procedencia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('tipo')->default(User::USUARIO_ASISTENTE);
            $table->string('verificado')->default(User::USUARIO_NO_VERIFICADO);
            $table->string('token_verificacion')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
