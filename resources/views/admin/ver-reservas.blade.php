@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}"> 

<div class="container">
    @if(session('reserva-modificada'))
    <div id="alerta-reserva-modificada" class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('reserva-modificada') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    <h2 class="mt-4 mb-4">Reservas de {{ $usuario->usuario }}</h2>

    <div class="reserva-container scrollable-container">
        @forelse ($reservas as $reserva)
        <div class="card">
            <div class="card-body">
                <p>Restaurante: {{ $reserva->restaurante->nombre }}</p>
                <p>Fecha de reserva: {{ optional($reserva->fecha_reserva)->format('Y-m-d') }}</p>
                <p>Hora de reserva: {{ $reserva->hora }}</p>
                <form method="post" action="{{ route('admin.reservas.cancelar', $reserva->id) }}" style="display:inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('¿Seguro que quieres cancelar esta reserva?')">Cancelar Reserva</button>
                </form>

                <a href="{{ route('admin.reservas.modificar-reserva', $reserva->id) }}" class="btn btn-warning btn-sm">Modificar Reserva</a>
            </div>
        </div>

        @if(session('reserva-cancelada-' . $reserva->id))
        <div id="alerta-reserva-cancelada-{{ $reserva->id }}" class="alert alert-success alert-dismissible fade show mt-4" role="alert">
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
    // Cierra automáticamente la alerta después de 10 segundos
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
