<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHorarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_horarios', function (Blueprint $table) {
            $table->SmallIncrements('id')->unsigned();
            $table->smallInteger('min_tolerancia');           
            $table->time('hora_entrada_inicio');
            $table->time('hora_entrada_fin');
            $table->time('hora_salida_inicio');
            $table->time('hora_salida_fin');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_horarios');
    }
}
