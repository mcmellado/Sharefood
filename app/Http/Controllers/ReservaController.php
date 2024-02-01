<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Restaurante;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaCancelada;

class ReservaController extends Controller
{
    public function nuevaReserva($slug)
{
    $restaurante = Restaurante::where('slug', $slug)->firstOrFail();

    $fecha = now()->toDateString();
    $restauranteId = $restaurante->id;

    $controller = new ReservaController();
    $horasDisponibles = $controller->obtenerHorasDisponibles(new Request([
        'fecha' => $fecha,
        'restaurante_id' => $restauranteId,
    ]));
    return view('nueva_reserva', compact('restaurante', 'horasDisponibles'));

}
    public function guardarReserva(Request $request, $slug)
    {
        $restaurante = Restaurante::where('slug', $slug)->firstOrFail();
        $restauranteId = $restaurante->id;

        Reserva::create([
            'usuario_id' => auth()->id(),
            'restaurante_id' => $restauranteId,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'cantidad_personas' => $request->cantidad_personas
        ]);

        session()->flash('reserva-confirmada', 'Tu reserva ha sido confirmada. ¡Gracias por elegir nuestro restaurante!');

        return redirect()->route('restaurantes.perfil', ['slug' => $slug]);
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

        public function cancelarReservaRestaurante(Request $request, Reserva $reserva)
{
    $justificacion = $request->input('justificacion', 'Sin justificación');

    Mail::to($reserva->usuario->email)->send(
        new ReservaCancelada(
            $reserva->usuario->usuario,
            $reserva->restaurante->nombre,
            $justificacion
        )
    );

    $reserva->delete();

    return redirect()->back()->with('success', 'Reserva cancelada correctamente');
}


}
