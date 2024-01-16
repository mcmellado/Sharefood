<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductosTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        DB::table('productos')->truncate();

        DB::statement('SET CONSTRAINTS ALL IMMEDIATE');

        $restaurantes = DB::table('restaurantes')->get();

        foreach ($restaurantes as $restaurante) {
            $productos = [
                [
                    'nombre' => 'Plato Especial 1 - ' . $restaurante->nombre,
                    'descripcion' => 'Delicioso plato especial del chef',
                    'precio' => 15.99,
                    'restaurante_id' => $restaurante->id,
                ],
                [
                    'nombre' => 'Plato Especial 2 - ' . $restaurante->nombre,
                    'descripcion' => 'Otra deliciosa creación del chef',
                    'precio' => 18.99,
                    'restaurante_id' => $restaurante->id,
                ],
                [
                    'nombre' => 'Plato Especial 3 - ' . $restaurante->nombre,
                    'descripcion' => 'Increíble plato de la casa',
                    'precio' => 22.50,
                    'restaurante_id' => $restaurante->id,
                ],
                [
                    'nombre' => 'Plato Especial 4 - ' . $restaurante->nombre,
                    'descripcion' => 'Especialidad de la semana',
                    'precio' => 19.99,
                    'restaurante_id' => $restaurante->id,
                ],
                [
                    'nombre' => 'Postre Especial - ' . $restaurante->nombre,
                    'descripcion' => 'Irresistible postre para finalizar',
                    'precio' => 8.99,
                    'restaurante_id' => $restaurante->id,
                ],
                [
                    'nombre' => 'Bebida Refrescante - ' . $restaurante->nombre,
                    'descripcion' => 'La mejor opción para acompañar tu comida',
                    'precio' => 4.50,
                    'restaurante_id' => $restaurante->id,
                ],
            ];

            DB::table('productos')->insert($productos);
        }
    }
}
