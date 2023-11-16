<?php

namespace App\Http\Controllers;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
    
        return view('restaurantes.index');
    }

    public function buscar(Request $request)
    {
        $query = $request->input('q');

        $restaurantes = Restaurante::where('nombre', 'ilike', "%$query%")
            ->orWhere('direccion', 'ilike', "%$query%")
            ->orWhere('sitio_web', 'ilike', "%$query%")
            ->orWhere('telefono', 'ilike', "%$query%")
            ->get();

        return view('restaurantes.resultados', compact('restaurantes', 'query'));
    }
}
