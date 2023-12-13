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
        'id_usuario'
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
    
    
}
