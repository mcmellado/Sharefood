<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;    

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        $users = User::orderBy('id')->get();
        return view('admin.panel_admin', compact('users'));
    }


    public function validar($id)
    {
        $user = User::find($id);
        $user->validacion = !$user->validacion; 
        $user->save();

        return redirect()->route('admin.panel_admin');
    }

    public function eliminar($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.panel_admin');
    }

    public function mostrarFormularioModificar($usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);
        return view('admin.perfil-modificar-admin', compact('usuario'));
    }

    public function modificarPerfil(Request $request, $usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);

        $request->validate([
            'email' => 'required|email',
            'telefono' => 'nullable|string',
            'biografia' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->biografia = $request->biografia;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
            $rutaImagen = public_path('images/' . $nombreImagen);
            $imagen->move(public_path('images'), $nombreImagen);
            $usuario->imagen = $nombreImagen;
        }

        $usuario->save();

        return redirect()->route('admin.panel_admin')->with('success', 'Perfil de usuario actualizado correctamente');
    }


    public function mostrarFormularioCambiarContrasena($usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);
        return view('admin.cambiar-contrasena-admin', compact('usuario'));
    }

    public function cambiarContrasena(Request $request, $usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->route('admin.panel_admin')->with('success', 'Contraseña de usuario cambiada correctamente');
    }

}
