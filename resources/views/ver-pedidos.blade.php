

@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Mis Pedidos</h1>

            @if(count($pedidos) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Precio total</th>
                            <th>Dirección</th>
                            <th>Fecha del Pedido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->precio_total }} €</td>
                                <td>{{ $pedido->direccion }}</td>
                                <td>{{ $pedido->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tienes pedidos.</p>
            @endif

            <div class="mt-4">
                <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary">
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
