<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mensajes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usuario_id');
        $table->unsignedBigInteger('otro_usuario_id');
        $table->text('mensaje');
        $table->timestamps();
    
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
