@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/ver_pediodos_restaurante.css') }}">



    <style>
        body {
            background-color: #D5EB9B;
            color: white;
        }

        .container {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .card {
            background-color: #343a40;
            color: white;
            border-radius: 10px;
            margin-top: 20px;
        }

        .card-body {
            padding: 20px;
        }

        th, td, table {
            background-color: #343a40!important;
            color: white!important;
            text-align: center;
        }

        li, ul {
            list-style-type: none;
        }

    
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
            width: 100px;
        }
    </style>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Pedidos del Restaurante {{ $restaurante->nombre }}:</h1>

                @if(count($pedidos) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Precio Total</th>
                            <th>Dirección</th>
                            <th>Fecha del pedido:</th>
                            <th>Platos Pedidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->usuario->usuario }}</td> 
                                <td>{{ $pedido->precio_total }} €</td>
                                <td>{{ $pedido->direccion }}</td>
                                <td>{{ $pedido->created_at }}</td>
                                <td>
                                    <ul>
                                        @foreach($pedido->platos as $plato)
                                            <li>{{ $plato->nombre }} - {{ $plato->precio }} €</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay pedidos registrados para este restaurante.</p>
            @endif

                <div class="mt-4">
                    <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger ml-2">
                        <i class="fas fa-arrow-left"></i> 
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
