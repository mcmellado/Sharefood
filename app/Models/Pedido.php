<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['usuario_id', 'restaurante_id', 'estado', 'precio_total', 'direccion'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class, 'restaurante_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class)->withPivot('cantidad', 'precio_total');
    }

    public function getPlatosAttribute()
    {
        return json_decode($this->attributes['platos']);
    }

}
