@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/ver-pedidos-restaurantes.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/ver-pedidos-usuarios.css') }}">

    <div class="container mt-5">
        <h1 class="mb-4" style="color: black;">Pedidos del Restaurante: {{ $restaurante->nombre }}</h1>

        @if(count($pedidos) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Restaurante</th>
                        <th>Estado</th>
                        <th>Precio Total</th>
                        <th>Dirección</th>
                        <th>Platos</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->usuario->usuario }}</td>
                            <td>{{ $pedido->restaurante->nombre }}</td> 
                            <td>{{ $pedido->estado }}</td>
                            <td>{{ $pedido->precio_total }}</td>
                            <td>{{ $pedido->direccion }}</td>
                            <td>
                                <ul>
                                    @foreach($pedido->platos as $plato)
                                        @php
                                            $nombrePartes = explode(' - ', $plato->nombre);
                                            $nombrePlato = $nombrePartes[0];
                                        @endphp
                                        <li>
                                            {{ $nombrePlato }} - 
                                            Cantidad: {{ $plato->cantidad }} - 
                                            Precio: {{ $plato->precio }} €
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $pedido->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay pedidos registrados para este restaurante.</p>
        @endif
    </div>
@endsection
