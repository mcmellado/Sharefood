<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HorariosTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        // Vaciar la tabla antes de insertar nuevos registros
        DB::table('horarios')->truncate();

        DB::statement('SET CONSTRAINTS ALL IMMEDIATE');

        // Obtén los IDs de los restaurantes para asociar los horarios
        $restaurantes = DB::table('restaurantes')->select('id')->get();

        foreach ($restaurantes as $restaurante) {
            $this->seedHorarios($restaurante->id);
        }
    }

    protected function seedHorarios($restauranteId)
{
    $dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

    foreach ($dias as $dia) {
        list($horaApertura, $horaCierre) = $this->generateRealisticTimeRange();
        
        $horaApertura->modify('+30 minutes');
        $horaCierre->modify('+30 minutes');

        $horario = [
            'restaurante_id' => $restauranteId,
            'dia_semana' => $dia,
            'hora_apertura' => $horaApertura->format('H:00:00'),
            'hora_cierre' => $horaCierre->format('H:00:00'),
        ];

        DB::table('horarios')->insert($horario);
    }
}

protected function generateRealisticTimeRange()
{
    $horaAperturaComun = 11;
    $horaCierreComun = 22;

    $horaApertura = new \DateTime();
    $horaApertura->setTime(mt_rand($horaAperturaComun, $horaCierreComun - 2), 0);

    $horaCierre = new \DateTime();
    $horaCierre->setTime(mt_rand($horaApertura->format('H') + 2, $horaCierreComun), 0);

    return [$horaApertura, $horaCierre];
}

}
