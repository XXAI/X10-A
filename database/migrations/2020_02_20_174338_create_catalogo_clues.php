<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoClues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_clues', function (Blueprint $table) {
            $table->string('clues', 14)->primary();
            /*$table->string('cve_jurisdiccion', 2);
            $table->string('nombre_unidad', 255);
            $table->string('estatus', 100);
            $table->smallInteger('clave_estatus',3);
            $table->string('longitud', 50);
            $table->string('latitud', 50);
            $table->string('nivel_atencion', 255);
            $table->smallInteger('clave_nivel',3);
            $table->string('estatus_acreditacion', 100);
            $table->smallInteger('responsable_id',5);
            $table->string('cargo_responsable', 255);*/
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
        Schema::dropIfExists('catalogo_clues');
    }
}
