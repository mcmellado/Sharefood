<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'sitio_web',
        'telefono',
        'imagen',
        'puntuacion',
        'slug',
        'id_usuario',
        'aforo_maximo',
        'tiempo_permanencia'
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function restaurante()
    {
        return view('restaurantes.restaurante');
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function propietario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
    
    
}
