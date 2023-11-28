<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registro');
    }
    public function register(Request $request)
    {
        $request->validate([
            'usuario' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
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
    
        return redirect()->route('index')->withSuccess('Usuario registrado y logado correctamente');

    }
}
