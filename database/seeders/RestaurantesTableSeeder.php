<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantesTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET CONSTRAINTS ALL DEFERRED');

        DB::table('restaurantes')->truncate();

        DB::statement('SET CONSTRAINTS ALL IMMEDIATE');

        DB::table('restaurantes')->insert([
            [
                'nombre' => 'La Parrilla del Valle',
                'direccion' => 'Av. Principal 123',
                'sitio_web' => 'https://www.parrilladelvalle.com',
                'telefono' => '+123456789',
                'gastronomia' => 'Asados',
            ],
            [
                'nombre' => 'Sabores del Mar',
                'direccion' => 'Calle 5, Zona Costera',
                'sitio_web' => 'https://www.saboresdelmar.com',
                'telefono' => '+987654321',
                'gastronomia' => 'Mariscos',
            ],
            [
                'nombre' => 'Pizzería Bella Italia',
                'direccion' => 'Via Roma 789',
                'sitio_web' => 'https://www.bellaitalia.com',
                'telefono' => '+112233445',
                'gastronomia' => 'Pizza',
            ],
            [
                'nombre' => 'Comida Mexicana Tradicional',
                'direccion' => 'Av. Hidalgo 456',
                'sitio_web' => 'https://www.mexicanatradicional.com',
                'telefono' => '+554433221',
                'gastronomia' => 'Mexicana',
            ],
            [
                'nombre' => 'Sushi Express',
                'direccion' => 'Calle Sushiman 101',
                'sitio_web' => 'https://www.sushiexpress.com',
                'telefono' => '+998877665',
                'gastronomia' => 'Sushi',
            ],
        ]);
    }
}
