<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Comentario;
use App\Models\Reserva;
use App\Models\Restaurante;
use Illuminate\Support\Carbon;
    

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

        return redirect()->route('admin.panel_admin')->with('usuario-eliminado', 'Usuario eliminado correctamente');
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

        return redirect()->route('admin.panel_admin')->with('usuario-modificado', 'Perfil de usuario actualizado correctamente');
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

        return redirect()->route('admin.panel_admin')->with('contrasena-cambiada', 'Contraseña de usuario cambiada correctamente');
    }

    public function verComentarios($usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);
        $comentarios = Comentario::where('usuario_id', $usuario->id)->get();
    
        foreach ($comentarios as $comentario) {
            if (!$comentario->fecha_publicacion instanceof Carbon) {
                $comentario->fecha_publicacion = Carbon::parse($comentario->fecha_publicacion);
            }
        }
    
        return view('admin.ver-comentarios', compact('usuario', 'comentarios'));
    }
    

    public function verReservas($usuarioId)
    {
        $usuario = User::findOrFail($usuarioId);
        $reservas = $usuario->reservas; 
    
        foreach ($reservas as $reserva) {
            if (!$reserva->fecha_reserva instanceof Carbon) {
                $reserva->fecha_reserva = Carbon::parse($reserva->fecha_reserva);
            }
        }
    
        return view('admin.ver-reservas', compact('usuario', 'reservas'));
    }
    
    public function eliminarComentario($comentarioId)
{
    $comentario = Comentario::findOrFail($comentarioId);

    $comentario->delete();

    return redirect()->back()->with('comentario-eliminado', 'El comentario ha sido eliminado.');
}

public function cancelarReserva($reservaId)
{
    $reserva = Reserva::findOrFail($reservaId);
    $reserva->delete();

    return redirect()->back()->with('reserva-cancelada', 'La reserva ha sido cancelada exitosamente.');
}

public function mostrarFormularioModificarReserva($reservaId)
{
    $reserva = Reserva::findOrFail($reservaId);
    $reserva->fecha = Carbon::parse($reserva->fecha);
    return view('admin.modificar-reserva-admin', compact('reserva'));
}

public function modificarReserva(Request $request, $reservaId)
{
    $reserva = Reserva::findOrFail($reservaId);

    $request->validate([
        'cantidad_personas' => 'required|integer|min:1',
    ]);

    $nuevaCantidadPersonas = $request->input('cantidad_personas');

    if ($request->has(['nueva_fecha', 'nueva_hora'])) {
        $request->validate([
            'nueva_fecha' => 'required|date',
            'nueva_hora' => 'required|date_format:H:i',
            'cantidad_personas' => 'sometimes|required|integer|min:1',
        ]);

        $nuevaFecha = $request->input('nueva_fecha');
        $nuevaHora = $request->input('nueva_hora');
        $nuevaFechaHora = "{$nuevaFecha} {$nuevaHora}";

        $reserva->fecha = $nuevaFecha;
        $reserva->hora = $nuevaFechaHora;
    }

    $reserva->cantidad_personas = $nuevaCantidadPersonas;
    $reserva->save();
    dd($request->all());

    return redirect()->route('admin.ver-reservas', ['usuarioId' => $reserva->usuario->id])->with('reserva-modificada', 'Reserva modificada exitosamente.');
}

    public function panelRestaurantes()
    {
        $restaurantes = Restaurante::orderBy('id')->get();
        return view('admin.panel-admin-restaurante', compact('restaurantes'));
    }
    public function eliminarRestaurante(Restaurante $restaurante)
    {
        $restaurante->delete();

        return redirect()->route('admin.panel-admin-restaurante')->with('success', 'Restaurante eliminado correctamente.');


    }

    public function modificarRestaurante($id)
{
    $restaurante = Restaurante::findOrFail($id);

    return view('admin.modificar-restaurante', compact('restaurante'));
}

    public function actualizarRestaurante(Request $request, $id)
        {
            $restaurante = Restaurante::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string|max:255',
                'direccion' => 'required|string|max:255',
                'sitio_web' => 'nullable|url|max:255',
                'telefono' => 'nullable|string|max:20',
                'gastronomia' => 'nullable|string|max:255',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
                
            ]);

            
            $restaurante->nombre = $request->input('nombre');
            $restaurante->direccion = $request->input('direccion');
            $restaurante->sitio_web = $request->input('sitio_web');
            $restaurante->telefono = $request->input('telefono');
            $restaurante->gastronomia = $request->input('gastronomia');

            
            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = public_path('images/' . $nombreImagen);
                $imagen->move(public_path('images'), $nombreImagen);
                $restaurante->imagen = $nombreImagen;
            }

            $restaurante->save();

            return redirect()->route('admin.panel-admin-restaurante')->with('success', 'Restaurante modificado correctamente.');
            

        }



} 


