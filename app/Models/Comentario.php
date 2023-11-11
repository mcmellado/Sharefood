<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = [
        'contenido',
        // Otras propiedades de tu modelo de comentario
    ];

    // Puedes definir relaciones con otros modelos aquí, por ejemplo, con usuarios o restaurantes
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }
}
