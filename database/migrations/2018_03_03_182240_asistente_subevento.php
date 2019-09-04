<?php

use App\Asistente;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AsistenteSubevento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistente_subevento', function (Blueprint $table) {
            $table->integer('evento_id')->unsigned();
            $table->integer('subevento_id')->unsigned();
            $table->integer('asistente_id')->unsigned();
            $table->string('estatus')->default(Asistente::ASISTENTE_REGISTRADO);
            $table->string('url_baucher')->nullable();
            $table->timestamps();

            $table->foreign('asistente_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subevento_id')->references('id')->on('subeventos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asistente_subevento');
    }
}
