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


}
