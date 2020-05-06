<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionTrimestral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracion_trimestral', function (Blueprint $table) {
            $table->Increments('id')->unsigned();
            $table->year('anio',4);
            $table->smallInteger('trimestre');
            $table->smallInteger('lote');
            $table->string('quincena',20);
            $table->smallInteger('no_documento');
            $table->smallInteger('user_id');
            $table->softDeletes();
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
        Schema::dropIfExists('configuracion_trimestral');
    }
}
