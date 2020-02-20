<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDiasOmisiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('omisiones', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->unsignedInteger("empleado_id");
            $table->unsignedInteger("incidencia_id");
            $table->string("tipo")->commnets("I = Entrada, S= Salida");
            $table->date("fecha");
            $table->time("hora");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('incidencia_id')->references('id')->on('incidencias');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('omisiones');
    }
}
