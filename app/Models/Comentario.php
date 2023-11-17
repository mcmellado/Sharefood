<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = 
    ['usuario_id', 
    'restaurante_id', 
    'contenido', 
    'imagen',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }
}
