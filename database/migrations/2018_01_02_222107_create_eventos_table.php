<?php
use App\Evento;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->text('descripcion');
            $table->string('url_imagen')->nullable();
            $table->string('url_mas_info')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_finalizacion');
            $table->string('visible')->default(Evento::EVENTO_NO_VISIBLE);
            //$table->double('precio_inscripcion')->unsigned()->nullable();
            $table->text('detalles_pago')->nullable();
            $table->integer('max_subeventos_elegibles')->unsigned()->nullable();
            //$table->integer('limite_asistentes')->unsigned()->nullable();
            $table->integer('organizador_id')->unsigned();
            $table->timestamps();

            $table->foreign('organizador_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eventos');
    }
}
