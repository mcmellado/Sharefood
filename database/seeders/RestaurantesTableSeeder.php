<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'puntuacion' => 0,
                'slug' => Str::slug('La Parrilla del Valle'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen1.jpg',
            ],
            [
                'nombre' => 'Sabores del Mar',
                'direccion' => 'Calle 5, Zona Costera',
                'sitio_web' => 'https://www.saboresdelmar.com',
                'telefono' => '+987654321',
                'gastronomia' => 'Mariscos',
                'puntuacion' => 0,
                'slug' => Str::slug('Sabores del Mar'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen2.jpg',
            ],
            [
                'nombre' => 'Pizzería Bella Italia',
                'direccion' => 'Via Roma 789',
                'sitio_web' => 'https://www.bellaitalia.com',
                'telefono' => '+112233445',
                'gastronomia' => 'Pizza',
                'puntuacion' => 5,
                'slug' => Str::slug('Pizzería Bella Italia'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen3.jpg',
            ],
            [
                'nombre' => 'Comida Mexicana Tradicional',
                'direccion' => 'Av. Hidalgo 456',
                'sitio_web' => 'https://www.mexicanatradicional.com',
                'telefono' => '+554433221',
                'gastronomia' => 'Mexicana',
                'puntuacion' => 0,
                'slug' => Str::slug('Comida Mexicana Tradicional'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen4.jpg',
            ],
            [
                'nombre' => 'Sushi Express',
                'direccion' => 'Calle Sushiman 101',
                'sitio_web' => 'https://www.sushiexpress.com',
                'telefono' => '+998877665',
                'gastronomia' => 'Sushi',
                'puntuacion' => 0,
                'slug' => Str::slug('Sushi Express'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen5.jpg',
            ],
            [
                'nombre' => 'Cafetería Aromas',
                'direccion' => 'Plaza Central 789',
                'sitio_web' => 'https://www.cafearomas.com',
                'telefono' => '+765432109',
                'gastronomia' => 'Café',
                'puntuacion' => 0,
                'slug' => Str::slug('Cafetería Aromas'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen6.jpg',
            ],
            [
                'nombre' => 'Rincón Argentino',
                'direccion' => 'Avenida del Asado 567',
                'sitio_web' => 'https://www.rinconargentino.com',
                'telefono' => '+987654321',
                'gastronomia' => 'Carnes',
                'puntuacion' => 0,
                'slug' => Str::slug('Rincón Argentino'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen7.jpg',
            ],
            [
                'nombre' => 'Vegetariano Verde',
                'direccion' => 'Calle Vegana 234',
                'sitio_web' => 'https://www.vegetarianoverde.com',
                'telefono' => '+123456789',
                'gastronomia' => 'Vegetariana',
                'puntuacion' => 0,
                'slug' => Str::slug('Vegetariano Verde'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen8.jpg',
            ],
            [
                'nombre' => 'Panadería Dulce Aroma',
                'direccion' => 'Calle del Pan 876',
                'sitio_web' => 'https://www.dulcearoma.com',
                'telefono' => '+112233445',
                'gastronomia' => 'Panadería',
                'puntuacion' => 0,
                'slug' => Str::slug('Panadería Dulce Aroma'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen9.jpg',
            ],
            [
                'nombre' => 'Bar Tapas y Copas',
                'direccion' => 'Plaza de Tapas 345',
                'sitio_web' => 'https://www.tapasycopas.com',
                'telefono' => '+554433221',
                'gastronomia' => 'Tapas',
                'puntuacion' => 0,
                'slug' => Str::slug('Bar Tapas y Copas'),
                'id_usuario' => 6,
                'imagen' => 'imagenes/imagen10.jpg',
            ],
        ]);
        
    }
}
