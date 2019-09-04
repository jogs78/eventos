<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubeventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subeventos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('url_imagen')->nullable();
            $table->dateTime('fecha');
            $table->string('lugar');
            //$table->double('precio_inscripcion')->unsigned()->nullable();
            $table->string('detalles_pago')->nullable();
            $table->integer('limite_asistentes')->unsigned()->nullable();
            $table->integer('evento_id')->unsigned();
            $table->timestamps();

            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subeventos');
    }
}
