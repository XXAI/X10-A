<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmpleadoPeriodo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_periodo', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->unsignedInteger("empleado_id");
            $table->date("fecha_inicio");
            $table->date("fecha_final");
           
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('empleado_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado_periodo');
    }
}
