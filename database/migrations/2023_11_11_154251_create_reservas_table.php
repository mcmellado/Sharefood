<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users'); 
            $table->foreignId('restaurante_id')->constrained('restaurantes'); 
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();
            $table->unsignedInteger('cantidad_personas'); 
            $table->boolean('completada')->default(false);
            $table->unsignedInteger('duracion')->default(60)->comment('Duración estimada de la reserva en minutos');
            $table->boolean('leido')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
}
