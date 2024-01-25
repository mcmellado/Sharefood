<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistroRestauranteController extends Controller
{

    public function index()
    {
        $usuario = auth()->user();
        $notificaciones = [];
    
        if ($usuario) {
            $usuario->misRestaurantes->each(function ($restaurante) use (&$notificaciones) {
                $nuevasReservas = $restaurante->reservas()->where('leido', false)->count();
                $nuevosPedidos = $restaurante->pedidos()->where('leido', false)->where('estado', 'pagado')->count();
    
                if ($nuevasReservas > 0) {
                    $restaurante->reservas()->update(['leido' => true]);
                    $notificaciones[] = [
                        'mensaje' => "Tienes nuevas reservas en {$restaurante->nombre}.",
                        'enlace' => route('restaurantes.verReservas', ['slug' => $restaurante->slug])
                    ];
                }
    
                if ($nuevosPedidos > 0) {
                    $restaurante->pedidos()->where('estado', 'pagado')->update(['leido' => true]);
    
                    $notificaciones[] = [
                        'mensaje' => "Tienes nuevos pedidos en {$restaurante->nombre}.",
                        'enlace' => route('restaurantes.ver_pedidos', ['slug' => $restaurante->slug])
                    ];
                }
            });
    
            if (!empty($notificaciones)) {
                session()->flash('notificaciones', $notificaciones);
                if (url()->current() != route('index')) {
                    return redirect()->route('index');
                }
            }
        }
    
        return view('index');
    }
    
    
    
    public function registroRestaurante()
    {
        return view('registro-restaurante');
    }

    public function validarRegistro(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'usuario' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users', 
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
        $restaurante->slug = Str::slug($request->nombre);
        $restaurante->id_usuario = $user->id;
        $restaurante->save();
        

        return redirect()->route('index')->withSuccess('Usuario registrado y logado correctamente');
    }
}
