<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('USERID')->index();
            $table->dateTime('fecha_ini');
            $table->dateTime('fecha_fin');
            $table->integer('incidencias_tipo_id');
            $table->string('observaciones');            
            $table->string('autoriza');
            $table->string('documentos');
            $table->integer('idvalida')->nullable();
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
        Schema::dropIfExists('incidencias');
    }
}
