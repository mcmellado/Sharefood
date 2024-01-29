<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Producto;
use App\Models\Comentario;
use App\Models\Reserva;
use App\Models\Pedido;



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
        'tiempo_permanencia',
        'gastronomia',
        'tiempo_cierre'
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentario::class)->orderBy('id');
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

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    
    public function comentario()
    {
        return $this->hasMany(Comentario::class);
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

    public function puntuaciones()
{
    return $this->hasMany(Puntuacion::class, 'restaurante_id');
}

public function actualizarPuntuacionPromedio()
{
    $puntuacionPromedio = $this->puntuaciones()->avg('puntuacion');
    $this->puntuacion = $puntuacionPromedio * 2; 
    $this->save();
}

public function tieneReservasFuturas()
{
    return $this->reservas()
        ->where(function ($query) {
            $query->where('fecha', '>', now()->toDateString())
                  ->orWhere(function ($query) {
                      $query->where('fecha', '=', now()->toDateString())
                            ->where('hora', '>', now()->toTimeString());
                  });
        })
        ->exists();
}

public function pedidos()
{
    return $this->hasMany(Pedido::class, 'restaurante_id');
}

    
}
