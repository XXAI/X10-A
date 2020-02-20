<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmpleadoPeriodoDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_periodo_detalles', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->unsignedInteger("empleado_periodo_id");
            $table->unsignedSmallInteger("horario_id");
            $table->unsignedSmallInteger("dia_inicio");
            $table->unsignedSmallInteger("dia_fin");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('empleado_periodo_id')->references('id')->on('empleado_periodo');
            $table->foreign('horario_id')->references('id')->on('catalogo_horarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado_periodo_detalles');
    }
}
