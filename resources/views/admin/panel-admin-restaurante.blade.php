<!-- resources/views/admin/panel-admin-restaurante.blade.php -->
@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container">
        <h2 class="mt-4 mb-4">Bienvenido al Panel de Administrador de Restaurantes</h2>

        <div class="row">
            <div class="col-md-12">
                <h3>Restaurantes</h3>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Dirección</th>
                                <th class="text-center">Sitio Web</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Gastronomía</th>
                                <th class="text-center">Puntuación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($restaurantes as $restaurante)
                                <tr>
                                    <td class="text-center">{{ $restaurante->id }}</td>
                                    <td class="text-center">{{ $restaurante->nombre }}</td>
                                    <td class="text-center">{{ $restaurante->direccion }}</td>
                                    <td class="text-center">{{ $restaurante->sitio_web ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $restaurante->telefono ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $restaurante->gastronomia ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $restaurante->puntuacion }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No hay restaurantes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
