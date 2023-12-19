<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aforo extends Model
{
    protected $table = 'aforo';

    protected $fillable = [
        'restaurante_id',
        'fecha',
        'aforo_diario',
        'hora', 
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }
}
