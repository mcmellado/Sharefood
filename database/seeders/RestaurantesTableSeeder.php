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

        // Insertar datos de restaurantes con puntuación
        DB::table('restaurantes')->insert([
            [
                'nombre' => 'La Parrilla del Valle',
                'direccion' => 'Av. Principal 123',
                'sitio_web' => 'https://www.parrilladelvalle.com',
                'telefono' => '+123456789',
                'gastronomia' => 'Asados',
                'puntuacion' => 6,
            ],
            [
                'nombre' => 'Sabores del Mar',
                'direccion' => 'Calle 5, Zona Costera',
                'sitio_web' => 'https://www.saboresdelmar.com',
                'telefono' => '+987654321',
                'gastronomia' => 'Mariscos',
                'puntuacion' => 8,
            ],
            [
                'nombre' => 'Pizzería Bella Italia',
                'direccion' => 'Via Roma 789',
                'sitio_web' => 'https://www.bellaitalia.com',
                'telefono' => '+112233445',
                'gastronomia' => 'Pizza',
                'puntuacion' => 7,
            ],
            [
                'nombre' => 'Comida Mexicana Tradicional',
                'direccion' => 'Av. Hidalgo 456',
                'sitio_web' => 'https://www.mexicanatradicional.com',
                'telefono' => '+554433221',
                'gastronomia' => 'Mexicana',
                'puntuacion' => 9,
            ],
            [
                'nombre' => 'Sushi Express',
                'direccion' => 'Calle Sushiman 101',
                'sitio_web' => 'https://www.sushiexpress.com',
                'telefono' => '+998877665',
                'gastronomia' => 'Sushi',
                'puntuacion' => 8,
            ],
            [
                'nombre' => 'Cafetería Aromas',
                'direccion' => 'Plaza Central 789',
                'sitio_web' => 'https://www.cafearomas.com',
                'telefono' => '+765432109',
                'gastronomia' => 'Café',
                'puntuacion' => 7,
            ],
            [
                'nombre' => 'Rincón Argentino',
                'direccion' => 'Avenida del Asado 567',
                'sitio_web' => 'https://www.rinconargentino.com',
                'telefono' => '+987654321',
                'gastronomia' => 'Carnes',
                'puntuacion' => 9,
            ],
            [
                'nombre' => 'Vegetariano Verde',
                'direccion' => 'Calle Vegana 234',
                'sitio_web' => 'https://www.vegetarianoverde.com',
                'telefono' => '+123456789',
                'gastronomia' => 'Vegetariana',
                'puntuacion' => 8,
            ],
            [
                'nombre' => 'Panadería Dulce Aroma',
                'direccion' => 'Calle del Pan 876',
                'sitio_web' => 'https://www.dulcearoma.com',
                'telefono' => '+112233445',
                'gastronomia' => 'Panadería',
                'puntuacion' => 7,
            ],
            [
                'nombre' => 'Bar Tapas y Copas',
                'direccion' => 'Plaza de Tapas 345',
                'sitio_web' => 'https://www.tapasycopas.com',
                'telefono' => '+554433221',
                'gastronomia' => 'Tapas',
                'puntuacion' => 9,
            ],
        ]);
    }
}
