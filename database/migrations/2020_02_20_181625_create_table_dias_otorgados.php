<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDiasOtorgados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_otorgados', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->unsignedInteger("empleado_id");
            $table->unsignedInteger("incidencia_id");
            $table->date("fecha");
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
        Schema::dropIfExists('dias_otorgados');
    }
}
