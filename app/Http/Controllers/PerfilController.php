<?php


namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function show($nombreUsuario)
{
    // Obtén el usuario por su nombre de usuario
    $usuario = User::where('usuario', $nombreUsuario)->first();

    if (!$usuario) {
        // Maneja el caso en que el usuario no sea encontrado, por ejemplo, redirigiendo o mostrando un mensaje.
        abort(404);
    }

    return view('perfil', compact('usuario'));
}
}
