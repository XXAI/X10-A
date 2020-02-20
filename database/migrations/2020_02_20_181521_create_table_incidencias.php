<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIncidencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->unsignedInteger("empleado_id");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->unsignedSmallInteger("tipo_incidencia_id");
            $table->string("no_oficio", 256);
            $table->smallInteger("estatus")->default(0)->comments("0= no aplicado, 1 = aplicado");
            $table->unsignedInteger("user_id");
            $table->datetime("fecha_hora_aplicacion");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('tipo_incidencia_id')->references('id')->on('catalogo_tipo_incidencia');
            $table->foreign('user_id')->references('id')->on('users');
            
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
