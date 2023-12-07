<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $fillable = ['dia_semana', 'hora_apertura', 'hora_cierre'];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function horarios()
{
    return $this->hasMany(Horario::class);
}

}
