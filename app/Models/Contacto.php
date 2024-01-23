<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = [
        'usuario_id',
        'otro_usuario_id',
        'mensaje',
        'aceptada',
        'bloqueado',
        'estado'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function otroUsuario()
    {
        return $this->belongsTo(User::class, 'otro_usuario_id');
    }
}