@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/ver-reservas.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    

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
                                    <form method="post" action="{{ route('cancelar.reserva', ['reserva' => $reserva->id]) }}" style="display:inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">❌ Cancelar Reserva</button>
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
@endsection
