<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show($nombreUsuario)
    {
        $usuario = User::where('usuario', $nombreUsuario)->first();

        if (!$usuario) {
            abort(404);
        }

        return view('perfil', compact('usuario'));
    }

    public function mostrarFormularioModificar()
    {
        $usuario = Auth::user();
        return view('perfil-modificar', compact('usuario'));
    }

    public function modificarPerfil(Request $request)
    {
        $user = User::find(Auth::id());
    
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:255',
            'biografia' => 'nullable|string',
        ]);
    
        $user->email = $request->email;
        $user->telefono = $request->telefono;
        $user->biografia = $request->biografia;
    
        if ($request->hasFile('imagen')) {
            if ($user->imagen) {
                Storage::delete($user->imagen);
            }
    
            $rutaImagen = $request->file('imagen')->store('perfiles', 'public');
            $user->imagen = $rutaImagen;
    
            info('Ruta de la imagen guardada: ' . $rutaImagen);
        }
    
        $user->save();
    
        return redirect()->route('perfil', ['nombreUsuario' => $user->usuario])->withSuccess('Perfil modificado correctamente');
    }
    
}
