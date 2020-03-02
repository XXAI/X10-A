<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string("num_empleado", 10);
            $table->string("nombre", 100);
            $table->string("apellido_paterno", 50)->nullable();
            $table->string("apellido_materno", 50)->nullable();
            $table->string("rfc", 14);
            $table->string("codigo_id", 10);
            $table->smallInteger("ur_id", 11);
            $table->string("cr_id", 11);
            $table->smallInteger("calculable");//->comments("0 = si, 1= no, si entra en el proceso de calculo de asistencia");
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('codigo_id')->references('codigo')->on('catalogo_codigo');
            $table->foreign('ur_id')->references('id')->on('catalogo_ur');
            $table->foreign('cr_id')->references('cr')->on('catalogo_cr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
}
