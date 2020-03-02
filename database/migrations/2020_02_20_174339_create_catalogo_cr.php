<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoCr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogo_cr', function (Blueprint $table) {
            $table->string('cr', 11)->unique();
            $table->string('descripcion', 150);
            $table->string('clues', 14);
            $table->string('area', 10);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('clues')->references('clues')->on('catalogo_clues');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('catalogo_cr');
    }
}
