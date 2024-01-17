<?php

namespace App;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurante;


class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'restaurante_id'];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public static function obtenerProductosPorRestaurante($restauranteId)
    {
        return self::where('restaurante_id', $restauranteId)->get();
    }
}
