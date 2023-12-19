<?php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reserva;
use App\Models\Restaurante;

class VerificarAforo extends Component
{
    public $fecha;
    public $hora;
    public $aforoRestante;

    public function render()
    {
        return view('livewire.verificar-aforo');
    }

    public function verificarAforo()
    {
        // Lógica para verificar el aforo en el intervalo de tiempo proporcionado
        $restaurante = Restaurante::where('slug', $this->restaurante->slug)->firstOrFail();

        $inicioIntervalo = $this->fecha . ' ' . $this->hora;
        $finIntervalo = (new \DateTime($inicioIntervalo))->add(new \DateInterval('PT1H30M'))->format('Y-m-d H:i:s');

        $reservasEnIntervalo = Reserva::where('restaurante_id', $restaurante->id)
            ->whereBetween('fecha_hora', [$inicioIntervalo, $finIntervalo])
            ->where('completada', false)
            ->sum('cantidad_personas');

        $this->aforoRestante = $restaurante->aforo - $reservasEnIntervalo;
    }
}
