<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAforoTable extends Migration
{
    public function up()
    {
        Schema::create('aforo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurante_id');
            $table->date('fecha');
            $table->time('hora')->after('fecha')->nullable();
            $table->integer('aforo_disponible')->default(150);  // Cambiado de nullable a default(150)
            $table->integer('aforo_reservado_intervalo')->default(0);  // Cambiado de nullable a default(0)
            $table->timestamps();

            $table->foreign('restaurante_id')->references('id')->on('restaurantes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('aforo');
    }
}
