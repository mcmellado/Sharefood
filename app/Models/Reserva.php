<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'usuario_id',
        'restaurante_id',
        'fecha',
        'hora',
        'cantidad_personas',
        'completada'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function haPasadoDeFecha()
    {
        return now() > $this->fecha;
    }

    public static function usuarioHaHechoReservaEnRestaurante($usuarioId, $restauranteId)
{
    return self::where('usuario_id', $usuarioId)
        ->where('restaurante_id', $restauranteId)
        ->first(); 
}
}
