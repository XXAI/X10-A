<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChecadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleado_asistencia', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedInteger("empleado_id");
            $table->datetime("fecha_hora");
            $table->smallInteger("logid")->unsigned();
            
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
        Schema::dropIfExists('empleado_asistencia');
    }
}
