<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'usuario',
        'email',
        'telefono',
        'password',
        'is_admin',
        'validacion', 
        'imagen',
        'biografia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }

    public function restaurantes()
    {
        return $this->hasMany(Restaurante::class);
    }
}
