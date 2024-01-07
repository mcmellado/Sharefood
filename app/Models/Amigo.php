<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amigo extends Model
{
    protected $table = 'amigos_user';
    protected $fillable = ['user_id', 'amigo_id'];
}