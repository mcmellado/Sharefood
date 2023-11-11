<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'sitio_web',
        'telefono',
    ];

    // Puedes definir relaciones con otros modelos aquí, por ejemplo, comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}
