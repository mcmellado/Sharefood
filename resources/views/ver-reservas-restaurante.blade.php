@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Reservas del Restaurante {{ $restaurante->nombre }}:</h1>

            @if(count($reservas) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th class="text-center">Cantidad de Personas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</td>
                                <td class="text-center">{{ $reserva->cantidad_personas }}</td>
                                <td>
                                    @if (\Carbon\Carbon::parse($reserva->fecha . ' ' . $reserva->hora)->isFuture())
                                        <form id="formCancelarReserva{{ $reserva->id }}" method="post" style="display:inline">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-danger" onclick="confirmCancel({{ $reserva->id }})">❌ Cancelar Reserva</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Reserva pasada</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tienes reservas.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">Volver atrás</a>
                <a href="{{ route('restaurantes.ver_pedidos', ['slug' => $restaurante->slug]) }}" class="btn btn-primary">Ver Pedidos</a>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmCancel(reservaId) {
        Swal.fire({
            title: '¿Seguro que quieres cancelar esta reserva?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar reserva',
            cancelButtonText: 'No cancelar reserva'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.getElementById('formCancelarReserva' + reservaId);
                form.action = "{{ route('cancelar.reserva', ['reserva' => ':reservaId']) }}".replace(':reservaId', reservaId);
                form.submit();
            }
        });
    }
</script>

@endsection
