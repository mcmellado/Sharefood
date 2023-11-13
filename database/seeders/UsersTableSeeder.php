<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'usuario' => 'cele',
            'nombre' => 'NombreCele',
            'apellidos' => 'ApellidosCele',
            'email' => 'cele@example.com',
            'telefono' => '123456789',
            'password' => Hash::make('123'),
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'usuario' => 'usuario1',
            'nombre' => 'Nombre1',
            'apellidos' => 'Apellidos1',
            'email' => 'usuario1@example.com',
            'telefono' => '987654321',
            'password' => Hash::make('123'),
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'usuario' => 'usuario2',
            'nombre' => 'Nombre2',
            'apellidos' => 'Apellidos2',
            'email' => 'usuario2@example.com',
            'telefono' => '555555555',
            'password' => Hash::make('123'),
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'usuario' => 'usuario3',
            'nombre' => 'Nombre3',
            'apellidos' => 'Apellidos3',
            'email' => 'usuario3@example.com',
            'telefono' => '123123123',
            'password' => Hash::make('123'),
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'usuario' => 'usuario4',
            'nombre' => 'Nombre4',
            'apellidos' => 'Apellidos4',
            'email' => 'usuario4@example.com',
            'telefono' => '456456456',
            'password' => Hash::make('123'),
            'is_admin' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
