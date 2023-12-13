@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container mt-5">
        <h2>Reservas de {{ $usuario->usuario }}</h2>

        <div class="scrollable-table mt-4">
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
                                <td>{{ $reserva->fecha }}</td>
                                <td>{{ $reserva->hora }}</td>
                                <td>
                                    <form method="post" action="{{ route('cancelar.reserva', ['reserva' => $reserva->id]) }}" style="display:inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Cancelar Reserva</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tienes reservas.</p>
            @endif
        </div>
    </div>
@endsection
