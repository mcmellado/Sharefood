<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;

class ReservaController extends Controller
{
    public function nuevaReserva($restauranteId)
    {
        return view('nueva_reserva', ['restauranteId' => $restauranteId]);
    }

    public function guardarReserva(Request $request, $restauranteId)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
        ]);

        Reserva::create([
            'usuario_id' => auth()->id(),
            'restaurante_id' => $restauranteId,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
        ]);

        return redirect()->route('restaurantes.confirmarReserva', ['restauranteId' => $restauranteId]);
    }

    public function confirmarReserva($restauranteId)
{
    return view('confirmar_reserva', ['restauranteId' => $restauranteId]);
}

}
