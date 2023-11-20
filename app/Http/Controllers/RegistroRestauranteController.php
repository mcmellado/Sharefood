<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegistroRestauranteController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function validarRegistro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users', // Nueva regla para el email
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nombre.required' => 'El campo de nombre del restaurante es obligatorio.',
            'direccion.required' => 'El campo de dirección es obligatorio.',
            'usuario.required' => 'El campo de usuario es obligatorio.',
            'usuario.unique' => 'Este usuario ya está registrado.',
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Por favor, ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'El campo de contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        $user = new User();
    
        $user->usuario = $request->usuario;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
    
        $user->save();
        Auth::login($user);

        $restaurante = new Restaurante();
        $restaurante->nombre = $request->nombre;
        $restaurante->direccion = $request->direccion;
        $restaurante->id = $user->id; 
        $restaurante->save();

        return redirect()->route('index')->withSuccess('Usuario registrado y logado correctamente');
    }
}
