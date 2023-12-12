<!-- resources/views/admin/ver-reservas.blade.php -->

@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<div class="container">
    <h2 class="mt-4 mb-4">Reservas de {{ $usuario->usuario }}</h2>

    <div class="reserva-container">
        @forelse ($reservas as $reserva)
        <div class="card">
            <div class="card-body">
                <p>Restaurante: {{ $reserva->restaurante->nombre }}</p>
                <p>Fecha de reserva: {{ $reserva->fecha_reserva->format('Y-m-d') }}</p>
                <p>Hora de reserva: {{ $reserva->hora }}</p>
                <form method="post" action="{{ route('admin.reservas.cancelar', $reserva->id) }}" style="display:inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Seguro que quieres cancelar esta reserva?')">Cancelar Reserva</button>
                </form>

                <a href="{{ route('admin.reservas.modificar', $reserva->id) }}" class="btn btn-warning btn-sm">Modificar
                    Reserva</a>
            </div>
        </div>

        @if(session('reserva-cancelada-' . $reserva->id))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('reserva-cancelada-' . $reserva->id) }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @empty
        <p>No hay reservas.</p>
        @endforelse
    </div>
    <a href="{{ route('admin.panel_admin') }}" class="btn btn-primary btn-return-admin mt-3">Volver al Panel de
        Administrador</a>
</div>
@endsection
