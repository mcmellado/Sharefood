@extends('layouts.app')

<title>Ver pedidos</title>

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/ver_pedidos_restaurante.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="mb-4">Pedidos del Restaurante {{ $restaurante->nombre }}:</h1>

                @if(count($pedidos) > 0 && collect($pedidos)->contains('estado', 'pagado'))
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuario:</th>
                                <th>Precio:</th>
                                <th>Dirección:</th>
                                <th>Fecha:</th>
                                <th>Platos Pedidos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                @if($pedido->estado === 'pagado') <!-- Filtra solo los pedidos pagados -->
                                    <tr>
                                        <td>{{ $pedido->usuario->usuario }}</td> 
                                        <td>{{ $pedido->precio_total }} €</td>
                                        <td>{{ $pedido->direccion }}</td>
                                        <td>{{ $pedido->created_at->locale('es_ES')->format('d/m/Y H:i') }}</td>
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
                                            <form id="formCancelarPedido{{ $pedido->id }}" method="POST" action="/cancelar-pedido/{{ $pedido->id }}" style="display:inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-success" onclick="confirmCancelPedido({{ $pedido->id }})">Cancelar Pedido</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
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

    <script>
        function confirmCancelPedido(pedidoId) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción cancelará el pedido y enviará una notificación al usuario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cancelar pedido',
                html: '<textarea id="justificacionCancelacion" class="swal2-textarea" placeholder="Justificación de la cancelación" required></textarea>'
            }).then((result) => {
                if (result.isConfirmed) {
                    var justificacion = document.getElementById('justificacionCancelacion').value;
                    var form = document.getElementById('formCancelarPedido' + pedidoId);
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'justificacion';
                    input.value = justificacion;
                    form.appendChild(input);
                    form.action = "/cancelar-pedido/" + pedidoId;
                    form.submit();
                }
            });
        }
    </script>
@endsection
