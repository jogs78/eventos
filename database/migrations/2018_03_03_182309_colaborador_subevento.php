<?php

use App\Colaborador;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColaboradorSubevento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colaborador_subevento', function (Blueprint $table) {
            $table->integer('colaborador_id')->unsigned();
            $table->integer('subevento_id')->unsigned();
            $table->string('tipo')->default(Colaborador::COLABORADOR_AYUDANTE);
        
            $table->foreign('subevento_id')->references('id')->on('subeventos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('colaborador_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colaborador_subevento');
    }
}
