@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}">


<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Reservas de {{ $usuario->usuario }}</h1>

            {{-- Muestra las reservas --}}
            @if ($reservas->count() > 0)
                <div class="scrollable-table">
                    <table class="table">   
                        <thead>
                            <tr>
                                <th>Restaurante</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $reserva)
                                <tr>
                                    <td>{{ $reserva->restaurante->nombre }}</td>
                                    <td>{{ $reserva->fecha }}</td>
                                    <td>{{ $reserva->hora }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No tienes reservas.</p>
            @endif
        </div>
    </div>
</div>

@endsection
