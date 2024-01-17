@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Reservas del Restaurante {{ $restaurante->nombre }}</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Cantidad de Personas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->fecha }}</td>
                            <td>{{ $reserva->hora }}</td>
                            <td>{{ $reserva->cantidad_personas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">Volver atrás</a>
                <a href="{{ route('restaurantes.ver_pedidos', ['slug' => $restaurante->slug]) }}" class="btn btn-primary">Ver Pedidos</a>


            </div>
        </div>
    </div>
</div>

@endsection
