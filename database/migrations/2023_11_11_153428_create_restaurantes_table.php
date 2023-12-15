<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('sitio_web')->nullable();
            $table->string('telefono')->nullable();
            $table->string('gastronomia')->nullable();
            $table->string('imagen')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedTinyInteger('puntuacion')->default(0)->nullable(false)->unsigned()->comment('Puntuación del 0 al 10');
            $table->string('slug')->unique()->nullable();
            $table->unsignedInteger('aforo')->default(150);
            $table->unsignedInteger('tiempo_permanencia')->default(3630)->comment('Tiempo de permanencia en minutos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurantes');
    }

}
