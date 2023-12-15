<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Restaurante;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function nuevaReserva($slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
    
        $reservasActuales = Reserva::where('restaurante_id', $restaurante->id)
            ->where('fecha', now()->toDateString())
            ->where('completada', false)
            ->sum('cantidad_personas');
    
        $aforoDiario = $restaurante->aforo;
    
        $aforoPorDia = [
            now()->toDateString() => $restaurante->aforo, 
        ];
    
        return view('nueva_reserva', [
            'restaurante' => $restaurante,
            'aforoRestante' => $aforoDiario - $reservasActuales,
            'aforoDiario' => $aforoDiario,
            'aforoPorDia' => $aforoPorDia,
        ]);
    }

    public function guardarReserva(Request $request, $slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
        $restauranteId = $restaurante->id;

        // Verificar aforo
        $reservasActuales = Reserva::where('restaurante_id', $restauranteId)
            ->where('fecha', $request->fecha)
            ->where('completada', false)
            ->sum('cantidad_personas');

        $aforoRestante = $restaurante->aforo - $reservasActuales;

        if ($aforoRestante < $request->cantidad_personas) {
            return redirect()->back()->with('error', 'El restaurante no tiene suficiente aforo para esa cantidad de personas.');
        }

        $horaReserva = Carbon::parse($request->input('hora'));
        $horaSalida = $horaReserva->copy()->addSeconds($restaurante->tiempo_permanencia);

        $reservasEnElMismoPeriodo = Reserva::where('restaurante_id', $restauranteId)
            ->where('fecha', $request->input('fecha'))
            ->whereBetween('hora', [$horaReserva, $horaSalida])
            ->where('completada', false)
            ->sum('cantidad_personas');

        if ($reservasEnElMismoPeriodo > 0) {
            return redirect()->back()->with('error', 'Ya hay reservas en ese horario, por favor elija otro.');
        }

        Reserva::create([
            'usuario_id' => auth()->id(),
            'restaurante_id' => $restauranteId,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'cantidad_personas' => $request->cantidad_personas,
            'tiempo_expiracion' => now()->addSeconds($restaurante->tiempo_permanencia),
        ]);

        return redirect()->route('restaurantes.perfil', ['slug' => $slug])->with('success', 'Reserva realizada con éxito.');
    }

    public function confirmarReserva($slug)
    {
        return view('reservas.confirmar_reserva', ['slug' => $slug]);
    }

    public function cancelar(Reserva $reserva)
    {
        $reserva->delete();

        return redirect()->back()->with('success', 'Reserva cancelada correctamente.');
    }
}
