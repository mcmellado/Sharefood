@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/carta.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

    
    <div class="main-container">
        <div class="container mt-5">
            <div x-data="{ showMessage: true }">
                <template x-if="showMessage">
                    @if(Session::has('success_message'))
                        <div class="alert alert-success">
                            <span>{{ Session::get('success_message') }}</span>
                            <button x-on:click="showMessage = false" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
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

        <form x-data="{ productos: {}, address: '' }" x-init="initQuantities()" action="{{ route('realizar-pedido') }}" method="POST" @submit.prevent="submitForm">
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
                                <input type="number" class="cantidad form-control" name="productos[{{ $producto->id }}]" x-model="productos[{{ $producto->id }}]" min="0" placeholder="Cantidad:">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="row mt-3" x-show="hasAddress || {{ auth()->check() ? 'true' : 'false' }}">
                            <div class="col-md-4" x-show="!{{ auth()->check() }}">
                                @auth
                                    <h3>Dirección de Entrega:</h3>
                                    <div class="input-container">
                                        <input type="text" name="direccion" x-model="address" class="form-control input-corto" placeholder="Dirección" required>
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

            this.$refs.form.submit();
        }

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#confirmacionReserva').modal('show');
            });
        </script>
    </script>
@endsection
