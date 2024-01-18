@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/restaurante.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <div class="container mt-5 mb-5"> 
        <h2 class="mb-4 display-4">Nuestros restaurantes:</h2>
        <div class="row">
            @php
                $mejoresRestaurantes = \App\Models\Restaurante::orderByDesc('puntuacion')->get();
            @endphp

            @foreach($mejoresRestaurantes as $mejorRestaurante)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <img src="{{ asset($mejorRestaurante->imagen) }}" class="card-img-top" alt="Imagen del restaurante">
                        <div class="card-body">
                            <a href="{{ route('restaurantes.perfil', ['slug' => $mejorRestaurante->slug]) }}" class="nombre-restaurante" style="text-decoration: none;">
                                <h5 class="card-title">{{ $mejorRestaurante->nombre }}</h5>
                            </a>                                               
                            <p class="card-text">{{ $mejorRestaurante->gastronomia }}</p>
                            <div class="custom-star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $mejorRestaurante->puntuacion)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <br>
@endsection
