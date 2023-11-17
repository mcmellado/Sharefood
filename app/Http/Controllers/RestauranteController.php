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

    // Supongamos que tienes algunos datos de prueba
    $sugerencias = ['Sushi', 'Pizza', 'Parrilla', 'Vegetariano'];

    return response()->json($sugerencias);
}


}
