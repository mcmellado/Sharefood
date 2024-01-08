<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactosTable extends Migration
{

    public function up()
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('otro_usuario_id');
            $table->text('mensaje');
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada', 'bloqueada'])->default('pendiente');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('contactos');
    }
}
