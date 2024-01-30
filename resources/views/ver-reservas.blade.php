@extends('layouts.app')

<title> Ver reservas </title>


@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
    

    <div class="container mt-5">
        <h2 class="mb-4 display-4">Reservas de {{ $usuario->usuario }}:</h2>

        @if ($reservas->count() > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Restaurante</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->restaurante->nombre }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->hora)->format('H:i') }}</td>
                            <td>
                                @if (\Carbon\Carbon::parse($reserva->fecha . ' ' . $reserva->hora)->isFuture())
                                <form id="formCancelarReserva" method="post" style="display:inline">
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

        <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary" style="width: 100px;">
            <i class="fas fa-arrow-left"></i>     
        </a>
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
                    var form = document.getElementById('formCancelarReserva');
                    form.action = "{{ route('cancelar.reserva', ['reserva' => ':reservaId']) }}".replace(':reservaId', reservaId);
                    form.submit();
                }
            });
        }
    </script>
    
@endsection
