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
            $table->string("nombre", 100)->index();
            $table->string("apellido_paterno", 50)->index()->nullable();
            $table->string("apellido_materno", 50)->index()->nullable();
            $table->string("rfc", 14)->index();
            $table->string("codigo_id", 10)->index();
            $table->string("ur", 10)->index();
            $table->string("cr_id", 11)->index();
            $table->smallInteger("calculable")->default(0)->comments("0 = si, 1= no, si entra en el proceso de calculo de asistencia");
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
        Schema::dropIfExists('empleados');
    }
}
