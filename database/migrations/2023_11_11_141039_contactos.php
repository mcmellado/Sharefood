<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contactos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('usuario_id')->constrained('users'); // Usuario principal
            $table->foreignId('contacto_id')->constrained('users'); // Usuario de contacto
            $table->boolean('bloqueado')->default(false);
            // Otros campos relevantes para la relación de contacto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactos');
    }
};

