<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = [
        'usuario_id',
        'contacto_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function contacto()
    {
        return $this->belongsTo(User::class, 'contacto_id');
    }
}
