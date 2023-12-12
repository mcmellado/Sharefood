<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario; 
use App\Models\User;


class ComentarioController extends Controller
{
    public function mostrarComentarios($usuarioId)
    {
        $usuario = User::find($usuarioId);
        $comentarios = Comentario::where('usuario_id', $usuarioId)->get();

        return view('ruta.hacia.tu.vista', compact('usuario', 'comentarios'));
    }
}
