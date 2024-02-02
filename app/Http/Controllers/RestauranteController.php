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
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;




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
        'tiempo_cierre' => $request->input('tiempo_cierre'), 
        'slug' => $nuevoSlug,
    ]);

    if ($request->hasFile('imagen')) {
        if ($restaurante->imagen) {
            Storage::delete($restaurante->imagen);
        }

        $rutaImagen = $request->file('imagen')->store('restaurantes', 'public');
        $restaurante->update(['imagen' => $rutaImagen]);
    }

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
            'sitio_web' => 'nullable|url',
            'aforo_maximo' => 'required|integer|min:1',
        ]);
    
        $nuevoSlug = Str::slug($request->input('nombre'));
    
        $contador = 1;
        while (DB::table('restaurantes')->where('slug', $nuevoSlug)->exists()) {
            $nuevoSlug = Str::slug($request->input('nombre')) . '-' . $contador;
            $contador++;
        }
    
        $imagenRuta = null;
    
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenRuta = $imagen->store('restaurantes', 'public');
        }
    
        $restaurante = new Restaurante([
            'nombre' => $request->input('nombre'),
            'direccion' => $request->input('direccion'),
            'gastronomia' => $request->input('gastronomia'),
            'imagen' => $imagenRuta,
            'telefono' => $request->input('telefono'),
            'sitio_web' => $request->input('sitio_web'),
            'aforo_maximo' => $request->input('aforo_maximo'),
            'slug' => $nuevoSlug,
        ]);
    
        $restaurante->usuario()->associate(auth()->user());
        $restaurante->save();
    
        foreach(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) {
            $horariosApertura = $request->input("hora_apertura_$dia", []);
            $horariosCierre = $request->input("hora_cierre_$dia", []);
    
            if (!empty($horariosApertura) && !empty($horariosCierre) && count($horariosApertura) == count($horariosCierre)) {
                foreach ($horariosApertura as $key => $horaApertura) {
                    $horaCierre = $horariosCierre[$key];
    
                    $horario = new Horario([
                        'dia_semana' => $dia,
                        'hora_apertura' => $horaApertura,
                        'hora_cierre' => $horaCierre,
                    ]);
    
                    $horario->restaurante()->associate($restaurante);
                    $horario->save();
                }
            }
        }
    
        return redirect()->route('perfil.mis-restaurantes', ['nombreUsuario' => auth()->user()->usuario])->with('success', 'Restaurante creado exitosamente.');    
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


    public function borrarRestaurante($slug)
{
    $restaurante = Restaurante::where('slug', $slug)->first();
    if ($restaurante && $restaurante->usuario->id == auth()->user()->id) {
        $restaurante->horarios()->delete();
        $restaurante->productos()->delete();
        $restaurante->comentario()->delete();
        $restaurante->reservas()->delete();
        $restaurante->delete();

        return redirect()->route('perfil.mis-restaurantes',  ['nombreUsuario' => auth()->user()->usuario])->with('success', 'Restaurante borrado exitosamente.');
    }

    return redirect()->route('perfil.mis-restaurantes',  ['nombreUsuario' => auth()->user()->usuario])->with('error', 'No tienes permisos para borrar este restaurante.');
}

public function editarComentario($comentarioId, Request $request)
{
    $comentario = Comentario::findOrFail($comentarioId);

    if (auth()->user()->id == $comentario->usuario_id) {
        $comentario->contenido = $request->input('contenido');
        $comentario->modificado = true;
        $comentario->save();

        return redirect()->route('perfil.mis-restaurantes')->with('success', 'Comentario actualizado exitosamente.');
    }

    return redirect()->route('perfil.mis-restaurantes')->with('error', 'No tienes permisos para editar este comentario.');
}


public function actualizarComentario(Request $request)
{
    $comentarioId = $request->input('comentarioId');
    $nuevoContenido = $request->input('nuevoContenido');

    $comentario = Comentario::find($comentarioId);
    $comentario->contenido = $nuevoContenido;
    $comentario->modificado = true;
    $comentario->save();

    $comentarioActualizado = Comentario::find($comentarioId);

    return response()->json([
        'mensaje' => 'Comentario actualizado con éxito',
        'nuevoContenido' => $comentarioActualizado->contenido
    ]);
}

public function mostrarCarta($id)
{
    try {
        $restaurante = Restaurante::findOrFail($id);
        $horarios = DB::table('horarios')
            ->where('restaurante_id', $id)
            ->get();

        $productos = DB::table('productos')
            ->where('restaurante_id', $id)
            ->orderBy('id') 
            ->get();

        return view('carta', compact('restaurante', 'productos', 'horarios'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        abort(404, 'Restaurante no encontrado');
    }
}

public function gestionarCarta($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->first();

        if (!$restaurante) {
            abort(404);
        }

        $productos = Producto::where('restaurante_id', $restaurante->id)->orderBy('id', 'asc')->get();

        return view('gestionar_carta', compact('restaurante', 'productos'));
    }

    public function agregarProducto(Request $request, $slug)
{
    $restaurante = Restaurante::where('slug', $slug)->first();

    if (!$restaurante) {
        abort(404);
    }

    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $producto = new Producto();
    $producto->nombre = $request->nombre;
    $producto->descripcion = $request->descripcion;
    $producto->precio = $request->precio;
    $producto->restaurante_id = $restaurante->id;


    if ($request->hasFile('imagen')) {
        $imagenPath = $request->file('imagen')->store('productos', 'public');
        $producto->imagen = $imagenPath;
    }

    $producto->save();

    return redirect()->route('restaurantes.gestionar_carta', ['slug' => $slug])
        ->with('success', 'Producto agregado correctamente');
}

    public function editarProducto($slug, $id)
{
    $restaurante = Restaurante::where('slug', $slug)->first();

    if (!$restaurante) {
        abort(404);
    }

    $producto = Producto::findOrFail($id);

    return view('editar_producto', compact('producto', 'restaurante'));
}

public function actualizarProducto(Request $request, $slug, $id)
{
    $producto = Producto::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
    ]);

    $producto->update([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'precio' => $request->precio,
    ]);

    if ($request->hasFile('imagen')) {
        $imagen = $request->file('imagen');
        $nombreImagen = time() . '.' . $imagen->getClientOriginalExtension();
        $imagen->storeAs('public/imagenes_productos', $nombreImagen);

        if ($producto->imagen) {
            Storage::delete('public/' . $producto->imagen);
        }
        $producto->imagen = 'imagenes_productos/' . $nombreImagen;
        $producto->save();
    }

    return redirect()->route('restaurantes.gestionar_carta', ['slug' => $slug])
        ->with('success', 'Producto actualizado correctamente');
}
    public function eliminarProducto($slug, $id)
    {
        $producto = Producto::findOrFail($id);

        $producto->delete();

        return redirect()->route('restaurantes.gestionar_carta', ['slug' => $slug])
            ->with('success', 'Producto eliminado correctamente');
    }

    public function formularioAgregarProducto($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->first();

        if (!$restaurante) {
            abort(404);
        }

        return view('agregar_producto', compact('restaurante'));
    }


    public function modificarHoras($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->first();
    
        $horarios = Horario::where('restaurante_id', $restaurante->id)->get();
    
        return view('modificar-horario', ['restaurante' => $restaurante, 'horarios' => $horarios]);
    }

    public function guardarHoras(Request $request, $slug)
    {
        foreach ($request->input('hora_apertura') as $horarioId => $horaApertura) {
            $horario = Horario::find($horarioId);
            $horario->hora_apertura = $horaApertura;
            $horario->save();
        }

        foreach ($request->input('hora_cierre') as $horarioId => $horaCierre) {
            $horario = Horario::find($horarioId);
            $horario->hora_cierre = $horaCierre;
            $horario->save();
        }

        return redirect()->route('perfil.mis-restaurantes',  ['nombreUsuario' => auth()->user()->usuario])->with('success', 'Horario restaurante modificado.');
        
    }

}


