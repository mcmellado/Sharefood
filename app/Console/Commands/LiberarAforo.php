<?php

namespace App\Console\Commands;


use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LiberarAforo extends Command
{
    protected $signature = 'reservas:liberar-aforo';
    protected $description = 'Libera el aforo de las reservas vencidas.';

    public function handle()
    {
        $reservasVencidas = Reserva::where('tiempo_expiracion', '<=', now())
            ->where('completada', false)
            ->get();

        foreach ($reservasVencidas as $reserva) {
            $reserva->completada = true;
            $reserva->save();
        }

        $this->info('Aforo liberado exitosamente.');
    }
}
