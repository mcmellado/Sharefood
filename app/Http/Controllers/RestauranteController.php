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

        // Realiza la búsqueda en la base de datos utilizando Eloquent
        $restaurantes = Restaurante::where('nombre', 'like', '%' . $query . '%')
            ->orWhere('descripcion', 'like', '%' . $query . '%')
            ->get();

        return view('restaurantes.resultados', compact('restaurantes', 'query'));
    }

}
