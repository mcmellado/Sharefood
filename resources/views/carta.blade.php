@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="{{ asset('css/carta.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

<div class="container mt-5">
    <div x-data="{ showMessage: true, hasAddress: false }">
        <template x-if="showMessage">
            @if(Session::has('success_message'))
                <div class="alert alert-success">
                    <span>{{ Session::get('success_message') }}</span>
                    <button x-on:click="showMessage = false" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(Session::has('cancel_message'))
                <div class="alert alert-warning">
                    <span>{{ Session::get('cancel_message') }}</span>
                    <button x-on:click="showMessage = false" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="my-custom-heading">Carta de "{{ $restaurante->nombre }}":</h3>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <form x-data="{ productos: {}, address: '' }" x-init="initQuantities()" action="{{ route('realizar-pedido') }}" method="POST" @submit.prevent="submitForm">
            @csrf
            <input type="hidden" name="restaurante_id" value="{{ $restaurante->id }}">

            @if ($productos->isNotEmpty())
            <div class="card mt-4 scrollable-container">
                <div class="card-body">
                    @foreach ($productos as $producto)
                    <div class="mb-4 border-bottom">
                        <h4 class="font-weight-bold">{{ $producto->nombre }}</h4>
                        <p class="mb-2">{{ $producto->descripcion }}</p>
                        <p class="font-weight-bold">Precio: {{ number_format($producto->precio, 2) }} €</p>
                        <input type="number" class="cantidad" name="productos[{{ $producto->id }}]" x-model="productos[{{ $producto->id }}]" min="0" class="form-control" placeholder="Cantidad">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="row mt-3" x-show="hasAddress || {{ auth()->check() ? 'true' : 'false' }}">
                <div class="col-md-6" x-show="!{{ auth()->check() }}">
                    @auth
                        <label for="delivery_address">Dirección de Entrega:</label>
                        <input type="text" name="direccion" x-model="address" class="form-control" placeholder="Ingrese su dirección" required>
                    @endauth

                </div>
                <div class="col-md-12 mt-2">
                    @auth
                        <button type="submit" class="btn btn-primary">Realizar Pedido</button>
                    @endauth
                    <a href="{{ route('restaurantes.perfil', ['slug' => $restaurante->slug ]) }}" class="btn btn-secondary ml-2">Volver al Perfil</a>
                </div>
            </div>

            <div id="paypal-button-container"></div>

            @else
            <p class="mt-4">No hay productos disponibles en la carta.</p>
            @endif
        </form>
    </div>
</div>

<script>
    function initQuantities() {
        // Inicializar cantidades a 0
        @foreach ($productos as $producto)
        this.productos['{{ $producto->id }}'] = 0;
        @endforeach
    }

    function submitForm() {
        if (!this.address && !{{ auth()->check() }}) {
            alert('Por favor, ingrese una dirección de entrega.');
            return;
        }

        // Agregar aquí cualquier otra lógica de envío de formulario si es necesario.

        // Envía el formulario
        this.$refs.form.submit();
    }
</script>
@endsection
