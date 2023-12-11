<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'usuario' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->usuario = $request->usuario;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        Auth::login($user);

        return redirect()->route('index')->withSuccess('Usuario registrado y logado correctamente');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario_correo' => 'required',
            'password' => 'required',
        ], [
            'usuario_correo.required' => 'El campo de usuario o correo es obligatorio.',
            'password.required' => 'El campo de contraseña es obligatorio.',
        ]);

        $credentialField = filter_var($request->input('usuario_correo'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'usuario';

        $user = User::where($credentialField, $request->input('usuario_correo'))->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['password' => 'El usuario no existe'])->withInput($request->except('password'));
        }

        $credentials = [
            $credentialField => $request->input('usuario_correo'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            if (!$user->validacion) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['password' => 'Usuario no validado']);
            }

            return redirect()->route('index')->withSuccess('Inicio de sesión correcto');
        }

        return redirect()->route('login')->withErrors(['password' => 'Las credenciales son incorrectas'])->withInput($request->except('password'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
