<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use App\Models\Comentario;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        $restaurantes = Restaurante::orderByDesc('puntuacion')->get();
      
    }

    public function mostrarPerfil($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

        return view('perfil-restaurante', compact('restaurante'));
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
            // Manejar el caso en el que no se encuentra el restaurante
            return redirect()->back()->with('error', 'No se encontró el restaurante para comentar.');
        }
    }
}

