@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <style>

        body {
        position: relative;
        font-family: 'Poppins', sans-serif!important;
        
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('../imagenes/imagen_fondo.jpg')!important;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(10px); 
        z-index: -1; 
    }

        .container {
            margin-top: 50px;
        }

        .card {
            border: 1px solid #343a40;
            border-radius: 10px;
            margin-top: 20px;
        }

        .card-body {
            padding: 20px;
            background-color: #343a40!important;
        }

        table {
            width: 100%;
            margin-top: 20px;

        }

        th, td, table {
            color: #fff!important;
            padding: 10px;
            text-align: center;
            background-color: #343a40!important;
        }

        th {
            background-color: #343a40;
        }

        tbody {
            background-color: #343a40;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        .btn-danger {
            width: 100px;
        }

        h1 {
            color: white;
        }

        p {
            color: white;

        }
    </style>

    
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Mis Pedidos:</h1>

            @if(count($pedidos) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Precio total:</th>
                            <th>Dirección:</th>
                            <th>Fecha del Pedido:</th>
                            <th> Local: </th>
                            <th>Platos Pedidos:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedidos as $pedido)
                            <tr>
                                <td>{{ $pedido->precio_total }} €</td>
                                <td>{{ $pedido->direccion }}</td>
                                <td>{{ $pedido->created_at }}</td>
                                <td> {{$pedido->restaurante->nombre}} </td>
                                <td>
                                    <ul>
                                        @foreach($pedido->platos as $plato)
                                        <li>
                                            {{ $plato->nombre }} - 
                                            Cantidad: {{ $plato->cantidad }} - 
                                            Precio: {{ $plato->precio }} € - 
                                        </li>
                                    @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No tienes pedidos.</p>
            @endif

        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">
            <i class="fas fa-arrow-left"></i> 
        </a>
    </div>
</div>
@endsection