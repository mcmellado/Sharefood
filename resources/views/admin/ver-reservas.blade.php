@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}"> 

<div class="container mt-5">
    @if(session('reserva-modificada'))
    <div id="alerta-reserva-modificada" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('reserva-modificada') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <h2 class="my-4">Reservas de {{ $usuario->usuario }}</h2>

    @forelse ($reservas as $reserva)
    <div class="card mb-4 reserva-card">
        <div class="card-body">
            <p class="mb-2">Restaurante: {{ $reserva->restaurante->nombre }}</p>
            <p class="mb-2">Fecha de reserva: {{ $reserva->fecha }}</p>
            <p class="mb-2">Hora de reserva: {{ $reserva->hora }}</p>
            <div class="btn-group" role="group" aria-label="Acciones de reserva">
                <form method="post" action="{{ route('admin.reservas.cancelar', $reserva->id) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm btn-cancelar-reserva" onclick="return confirm('¿Seguro que quieres cancelar esta reserva?')">Cancelar</button>
                </form>
                <a href="{{ route('admin.reservas.modificar-reserva', $reserva->id) }}" class="btn btn-warning btn-sm btn-modificar-reserva">Modificar</a>
            </div>
        </div>
    </div>

    @if(session('reserva-cancelada-' . $reserva->id))
    <div id="alerta-reserva-cancelada-{{ $reserva->id }}" class="alert alert-success alert-dismissible fade show" role="alert">
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
    <a href="{{ route('admin.panel_admin') }}" class="btn btn-primary btn-return-admin mt-3">Volver al Panel de Administrador</a>
</div>

<script>
    setTimeout(function() {
        var alertaReservaModificada = document.getElementById('alerta-reserva-modificada');
        if (alertaReservaModificada) {
            alertaReservaModificada.remove();
        }

        @forelse ($reservas as $reserva)
            var alertaReservaCancelada = document.getElementById('alerta-reserva-cancelada-{{ $reserva->id }}');
            if (alertaReservaCancelada) {
                alertaReservaCancelada.remove();
            }
        @empty
        @endforelse
    }, 10000);
</script>

@endsection
