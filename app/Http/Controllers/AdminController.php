<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Comentario;
use App\Models\Reserva;
use App\Models\Restaurante;
use App\Models\Horario;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Pedido;
use Illuminate\Validation\Rule;



    

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
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($usuarioId),
            ],
            'telefono' => 'nullable|string|max:255',
            'biografia' => 'nullable|string',
        ], [
            'email.unique' => 'Este correo electrónico ya está en uso por otro usuario.',
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

    // Obtén el restaurante asociado a la reserva
    $restaurante = $reserva->restaurante;

    return view('admin.modificar-reserva-admin', compact('reserva', 'restaurante'));
}

public function modificarReserva(Request $request, $reservaId)
{
    $reserva = Reserva::findOrFail($reservaId);

    $request->validate([
        'cantidad_personas' => 'required|integer|min:1',
        'nueva_fecha' => 'required|date',
        'nueva_hora' => 'required|date_format:H:i',
    ]);

    $nuevaCantidadPersonas = $request->input('cantidad_personas');
    $nuevaFecha = $request->input('nueva_fecha');
    $nuevaHora = $request->input('nueva_hora');
    $nuevaFechaHora = "{$nuevaFecha} {$nuevaHora}";

    if ($nuevaFechaHora !== "{$reserva->fecha} {$reserva->hora}") {
        $reservasEnNuevaFechaHora = Reserva::where('fecha', $nuevaFecha)
            ->where('hora', $nuevaHora)
            ->where('restaurante_id', $reserva->restaurante_id)
            ->where('id', '!=', $reservaId) 
            ->count();

        if ($reservasEnNuevaFechaHora > 0) {
            return redirect()->back()->with('error', 'Ya hay una reserva existente en la nueva fecha y hora seleccionadas.');
        }
    }

    $reserva->fecha = $nuevaFecha;
    $reserva->hora = $nuevaHora;
    $reserva->cantidad_personas = $nuevaCantidadPersonas;
    $reserva->save();

    return redirect()->route('admin.ver-reservas', ['usuarioId' => $reserva->usuario->id])->with('reserva-modificada', 'Reserva modificada exitosamente.');
}
    public function panelRestaurantes()
    {
        $restaurantes = Restaurante::orderBy('id')->get();
        return view('admin.panel-admin-restaurante', compact('restaurantes'));
    }
    public function eliminarRestaurante($id)
    {

        $restaurante = Restaurante::findOrFail($id);
 
        $restaurante->horarios()->delete();
        $restaurante->productos()->delete();
        $restaurante->comentario()->delete();
        $restaurante->reservas()->delete();
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
            $slug = $restaurante->slug;
            $nombreUsuario = $restaurante->propietario->usuario;
        
            $request->validate([
                'nombre' => 'required|string',
                'direccion' => 'required|string',
                'sitio_web' => 'nullable|string|url',
                'telefono' => 'nullable|string',
                'aforo_maximo' => 'required|integer',
                'tiempo_permanencia' => 'required|integer',
                'gastronomia' => 'nullable|string',
            ]);
        
            $nuevoSlug = Str::slug($request->input('nombre'));
            $contador = 1;
        
            while (Restaurante::where('slug', $nuevoSlug)->where('id', '!=', $restaurante->id)->exists()) {
                $nuevoSlug = Str::slug($request->input('nombre')) . '-' . $contador;
                $contador++;
            }
        
            $restaurante->update([
                'gastronomia' => $request->input('gastronomia'), 
                'nombre' => $request->input('nombre'),
                'direccion' => $request->input('direccion'),
                'sitio_web' => $request->input('sitio_web'),
                'telefono' => $request->input('telefono'),
                'aforo_maximo' => $request->input('aforo_maximo'), 
                'tiempo_permanencia' => $request->input('tiempo_permanencia'),
                'tiempo_cierre' => intval($request->input('tiempo_cierre')), 
                'slug' => $nuevoSlug,
            ]);
        
            if ($request->hasFile('imagen')) {
                if ($restaurante->imagen) {
                    Storage::delete($restaurante->imagen);
                }
        
                $rutaImagen = $request->file('imagen')->store('restaurantes', 'public');
                $restaurante->update(['imagen' => $rutaImagen]);
            }

            return redirect()->route('admin.panel-admin-restaurante')->with('success', 'Restaurante modificado correctamente.');
            

        }

        public function obtenerHorasDisponibles(Request $request)
        {
            $fecha = $request->input('fecha');
            $restauranteId = $request->input('restaurante_id');
        
            $diaSemana = Carbon::parse($fecha)->isoFormat('dddd');
        
            $horarios = Horario::where('restaurante_id', $restauranteId)
                ->where('dia_semana', $diaSemana)
                ->get();
        
            $horasDisponibles = [];
        
            foreach ($horarios as $horario) {
                $horaActual = Carbon::parse($horario->hora_apertura);
                $horaCierre = Carbon::parse($horario->hora_cierre);
        
                while ($horaActual < $horaCierre) {
                    $horasDisponibles[] = $horaActual->format('H:i');
                    $horaActual->addMinutes($horario->intervalo);
                }
            }
        
            return response()->json($horasDisponibles);
        }


        public function verPedidosRestaurantes($restauranteId)
{
    $restaurante = Restaurante::find($restauranteId);
    $pedidos = Pedido::where('restaurante_id', $restauranteId)->get();

    return view('admin.ver-pedidos-restaurantes-admin', compact('pedidos', 'restaurante'));
}



} 


