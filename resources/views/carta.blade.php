@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/carta.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="display-6">Carta de "{{ $restaurante->nombre }}":</h2>
                    </div>
                    <div class="col-md-4">
                        <!-- Puedes agregar una imagen del restaurante aquí si lo deseas -->
                    </div>
                </div>
            </div>
        </div>

        @if ($productos->isNotEmpty())
            <div class="card mt-4 scrollable-container">
                <div class="card-body">
                    <h3 class="mb-4">Carta del Restaurante:</h3>
                    @foreach ($productos as $producto)
                        <div class="mb-4 border-bottom">
                            <h4 class="font-weight-bold">{{ $producto->nombre }}</h4>
                            <p class="mb-2">{{ $producto->descripcion }}</p>
                            <p class="font-weight-bold">Precio: {{ number_format($producto->precio, 2) }} €</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver Atrás</a>
        
        @else
            <p class="mt-4">No hay productos disponibles en la carta.</p>
            <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver Atrás</a>
        @endif
    </div>
@endsection
