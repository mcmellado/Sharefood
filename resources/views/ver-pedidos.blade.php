@extends('layouts.app')

<title>Ver pedidos</title>

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/ver-pedidos-usuarios.css') }}">

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Mis Pedidos:</h1>

                @if(count($pedidos) > 0 && collect($pedidos)->contains('estado', 'pagado'))
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Precio:</th>
                                <th>Dirección:</th>
                                <th>Fecha:</th>
                                <th>Local: </th>
                                <th>Platos Pedidos:</th>
                                <th>Acciones:</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                @if($pedido->estado === 'pagado')
                                    <tr>
                                        <td>{{ $pedido->precio_total }} €</td>
                                        <td>{{ $pedido->direccion }}</td>
                                        <td>{{ $pedido->created_at->locale('es_ES')->format('d/m/Y H:i') }}</td>
                                        <td> {{ $pedido->restaurante->nombre }} </td>
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
                                        <td>
                                            <button class="btn btn-danger" onclick="cancelarPedido({{ $pedido->id }})">
                                                Cancelar
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No hay pedidos realizados.</p>
                @endif

            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <script>
        function cancelarPedido(pedidoId) {
            Swal.fire({
                title: 'Confirmar Cancelación',
                text: '¿Seguro que quieres cancelar este pedido?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`/cancelar-pedidoUsuario/${pedidoId}`)
                        .then(response => {
                            location.reload();
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error',
                                'Error al cancelar el pedido: ' + error.response.data.error,
                                'error'
                            );
                        });
                }
            });
        }
    </script>
@endsection
