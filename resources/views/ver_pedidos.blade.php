@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Pedidos del Restaurante {{ $restaurante->nombre }}</h1>

                @if(count($pedidos) > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID del Pedido</th>
                                <th>Precio Total</th>
                                <th>Dirección</th>
                                <th>Fecha del pedido:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido->id }}</td>
                                    <td>{{ $pedido->precio_total }} €</td>
                                    <td>{{ $pedido->direccion }}</td>
                                    <td>{{ $pedido->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No hay pedidos registrados para este restaurante.</p>
                @endif

                <div class="mt-4">
                    <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary">Ver Reservas</a>
                    <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger ml-2">Volver atrás</a>
                </div>
            </div>
        </div>
    </div>
@endsection
