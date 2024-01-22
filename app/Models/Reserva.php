<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    $fechaActual = Carbon::now();
    $fechaReserva = Carbon::parse($this->fecha);
    $horaReserva = Carbon::parse($this->hora);
    $fechaHoraReserva = $fechaReserva->setHour($horaReserva->hour)->setMinute($horaReserva->minute);
    return $fechaHoraReserva->lte($fechaActual);
}

        
public function puedePuntuar()
{
    return !$this->haPasadoDeFecha() && !$this->haPuntuadoRestaurante($this->restaurante_id) && $this->completada;
}

    public static function usuarioHaHechoReservaEnRestaurante($usuarioId, $restauranteId)
{
    return self::where('usuario_id', $usuarioId)
        ->where('restaurante_id', $restauranteId)
        ->first(); 
}

public function puntuaciones()
{
    return $this->hasMany(Puntuacion::class, 'reserva_id'); 
}


public function haPuntuadoRestaurante($restauranteId)
{
    return $this->puntuaciones()->where('restaurante_id', $restauranteId)->exists();
}
}
