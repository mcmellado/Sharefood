@extends('layouts.app')

<title> Carta </title>

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/carta.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    

    <div class="main-container">
        <div class="container mt-5">
            @if(Session::has('success_message'))
                <div class="alert alert-success">
                    <span>{{ Session::get('success_message') }}</span>
                    <button class="close" aria-label="Close" onclick="this.parentElement.style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Session::has('error_message'))
                <div class="alert alert-danger">
                    <span>{{ Session::get('error_message') }}</span>
                    <button class="close" aria-label="Close" onclick="this.parentElement.style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($restauranteCerrado === 'cerrado')
            <div class="alert alert-danger mt-4" role="alert">
                ¡Este restaurante se encuentra actualmente cerrado! Puedes seguir viendo la carta, pero ten en cuenta que no podrás realizar pedidos.
            </div>
            @endif
        
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="my-custom-heading">Carta de "{{ $restaurante->nombre }}":</h3>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
            </div>

            <form id="pedidoForm" action="{{ route('realizar-pedido') }}" method="POST" x-data="{ mostrarDireccion: false }" @submit.prevent="submitForm()">
                @csrf
                <input type="hidden" name="restaurante_id" value="{{ $restaurante->id }}">

                @if ($productos->isNotEmpty())
                    <div class="card mt-4">
                        <div class="card-body">
                            @foreach ($productos as $producto)
                                <div class="mb-4 border-bottom">
                                    <h4 class="font-weight-bold">{{ $producto->nombre }}</h4>
                                    <p class="mb-2">{{ $producto->descripcion }}</p>
                                    <p class="font-weight-bold">Precio: {{ number_format($producto->precio, 2) }} €</p>
                                    @if($producto->imagen)
                                        <div class="col-md-6" style="margin-bottom: 10px;">
                                            @if($producto->imagen)
                                                <img src="{{ '/storage/' . $producto->imagen }}" alt="{{ $producto->nombre }}" class="img-fluid">
                                            @endif
                                        </div>                                    
                                    @endif
                                    <label>
                                        Seleccionar plato: <input type="checkbox" onchange="toggleCantidadInput(event, {{ $producto->id }})" name="productos_checkbox[]"> 
                                    </label>
                                    <input type="number" class="cantidad form-control" name="productos[{{ $producto->id }}]" style="display: none;" disabled min="1" placeholder="Cantidad:" oninput="validarCantidad(event)" pattern="[0-9]+" required>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    @auth
                                        <h3 x-show="mostrarDireccion">Dirección de Entrega:</h3>
                                        <div class="input-container" x-show="mostrarDireccion">
                                            <input id="direccion" type="text" name="direccion" class="form-control input-corto" placeholder="Dirección" required>
                                            <ul id="direccionesGuardadas" class="list-group mt-2">
                                            </ul>
                                        </div>
                                    @endauth
                                </div>

                                <div class="col-md-12 mt-2">
                                    @auth
                                        <button type="submit" class="btn btn-success">Realizar Pedido</button>
                                    @endauth
                                    <a href="{{ route('restaurantes.perfil', ['slug' => $restaurante->slug ]) }}" class="btn btn-secondary ml-2">Volver al Perfil</a>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <p class="mt-4">No hay productos disponibles en la carta.</p>
                @endif
            </form>
        </div>
    </div>

    <script src="{{ asset('js/carta.js') }}" defer></script>
    
@endsection
