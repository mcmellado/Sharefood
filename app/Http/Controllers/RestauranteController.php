<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Horario;
use App\Models\Restaurante;
use App\Models\Comentario;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Puntuacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class RestauranteController extends Controller
{
    public function index()
    {
        $restaurantes = Restaurante::orderByDesc('puntuacion')->get();
      
    }

    public function mostrarPerfil($slug)
{
    $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
    $usuarioReserva = auth()->check() ? Reserva::usuarioHaHechoReservaEnRestaurante(auth()->user()->id, $restaurante->id) : null;
    $usuarioHaVotado = $usuarioReserva ? $this->usuarioHaVotado(auth()->user()->id, $restaurante->id) : false;

    return view('perfil-restaurante', compact('restaurante', 'usuarioReserva', 'usuarioHaVotado'));
}
    
    public function buscarSugerencias(Request $request)
    {
        $query = $request->input('q');

        $restaurantes = Restaurante::whereRaw('LOWER(nombre) LIKE ?', ["%".strtolower($query)."%"])
            ->orWhereRaw('LOWER(direccion) LIKE ?', ["%".strtolower($query)."%"])
            ->orWhereRaw('LOWER(gastronomia) LIKE ?', ["%".strtolower($query)."%"])
            ->limit(5)
            ->get();

        return response()->json($restaurantes);
    }

    public function comentar(Request $request, $slug)
    {
        $request->validate([
            'contenido' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        $restaurante = Restaurante::where('slug', $slug)->first();
    
        if ($restaurante) {
            $restauranteId = $restaurante->id;
    
            $comentario = new Comentario([
                'contenido' => $request->input('contenido'),
                'usuario_id' => auth()->user()->id, 
                'restaurante_id' => $restauranteId,
            ]);
    
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('comentarios', 'public');
                $comentario->imagen = $imagenPath;
            }
    
            $comentario->save();
    
            return redirect()->back()->with('success', 'Comentario agregado correctamente');
        } else {
            return redirect()->back()->with('error', 'No se encontró el restaurante para comentar.');
        }
    }

    public function eliminarComentario($comentarioId)
    {
        $comentario = Comentario::findOrFail($comentarioId);

        if (auth()->user()->id == $comentario->usuario_id) {
            $comentario->delete();
            return redirect()->back()->with('mensaje', 'Comentario eliminado exitosamente.');
        }

        return redirect()->back()->with('mensaje', 'No tienes permisos para eliminar este comentario.');
    }

    public function mostrarFormularioModificar($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

        return view('modificar-restaurante-usuario', compact('restaurante'));
    }

    public function modificarRestaurante(Request $request, $slug)
{
    $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

    $nombreUsuario = $restaurante->propietario->usuario;

    $request->validate([
        'nombre' => 'required|string',
        'direccion' => 'required|string',
        'sitio_web' => 'nullable|string|url',
        'telefono' => 'nullable|string',
        'aforo_maximo' => 'required|integer', 
    ]);

    $restaurante->update([
        'nombre' => $request->input('nombre'),
        'direccion' => $request->input('direccion'),
        'sitio_web' => $request->input('sitio_web'),
        'telefono' => $request->input('telefono'),
        'aforo_maximo' => $request->input('aforo_maximo'), 
        'slug' => Str::slug($request->input('nombre')),
    ]);

    return redirect()->route('perfil.mis-restaurantes', ['nombreUsuario' => $nombreUsuario])->withSuccess('Restaurante modificado correctamente');
}

public function verReservasRestaurante($slug)
{
    $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
    $reservas = $restaurante->reservas;

    return view('ver-reservas-restaurante', compact('reservas', 'restaurante'));
}


public function verComentariosRestaurante($slug)
{
    $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
    $comentarios = $restaurante->comentarios()->with('usuario')->get();

    return view('ver-comentarios-restaurante', compact('comentarios', 'restaurante'));
}

public function puntuar(Request $request, $slug)
{
    $request->validate([
        'puntuacion' => 'required|numeric|between:0,5',
    ]);

    $restaurante = Restaurante::where('slug', $slug)->first();

    if ($restaurante) {
        $usuario = auth()->user();

        if (Puntuacion::where('usuario_id', $usuario->id)->where('restaurante_id', $restaurante->id)->exists()) {
            return redirect()->back()->with('error', 'Ya has puntuado este restaurante anteriormente.');
        }

        Puntuacion::create([
            'usuario_id' => $usuario->id,
            'restaurante_id' => $restaurante->id,
            'puntuacion' => $request->input('puntuacion'),
        ]);

        return redirect()->back()->with('success', 'Puntuación agregada correctamente.');
    } else {
        return redirect()->back()->with('error', 'No se encontró el restaurante para puntuar.');
    }
}

    public function usuarioHaVotado($usuarioId, $restauranteId)

    {
        return Puntuacion::where('usuario_id', $usuarioId)
            ->where('restaurante_id', $restauranteId)
            ->exists();
    }

    public function registrarNuevoRestaurante(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'gastronomia' => 'nullable|string|max:255',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'intervalo' => 'required|integer|min:1',
        'sitio_web' => 'nullable|url', 
        'aforo_maximo' => 'required|integer|min:1',
    ]);

    $restaurante = new Restaurante([
        'nombre' => $request->input('nombre'),
        'direccion' => $request->input('direccion'),
        'gastronomia' => $request->input('gastronomia'),
        'imagen' => $request->input('imagen'),
        'telefono' => $request->input('telefono'),
        'sitio_web' => $request->input('sitio_web'),
        'aforo_maximo' => $request->input('aforo_maximo'),
        'slug' => Str::slug($request->input('nombre')),
    ]);

    $restaurante->usuario()->associate(auth()->user());
    $restaurante->save();

    foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) {
        $horaApertura = $request->input("hora_apertura_$dia", null);
        $horaCierre = $request->input("hora_cierre_$dia", null);

        if ($horaApertura !== null && $horaCierre !== null) {
            $horario = new Horario([
                'dia_semana' => $dia,
                'hora_apertura' => $horaApertura,
                'hora_cierre' => $horaCierre,
                'intervalo' => $request->input('intervalo'),
            ]);

            $horario->restaurante()->associate($restaurante);
            $horario->save();
        }
    }

    return redirect()->route('perfil', ['nombreUsuario' => auth()->user()->usuario])->with('success', 'Restaurante creado exitosamente.');
}

        public function formularioCrearRestaurante()
    {
        $usuario = Auth::user();
        if (!$usuario) {
            abort(404);
        }

        $restaurantes = $usuario->misRestaurantes;

        return view('crear-restaurante', compact('usuario', 'restaurantes'));
    }


}