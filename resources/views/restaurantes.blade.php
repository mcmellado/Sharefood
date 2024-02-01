@extends('layouts.app')

<title> Restaurantes </title>

@section('contenido')
    <link rel="stylesheet" href="{{ asset('css/restaurante.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">

    <div class="container mt-5 mb-5"> 
        <h2 class="mb-4 display-4">Nuestros restaurantes:</h2>
        <div class="row">
            @php
                $mejoresRestaurantes = \App\Models\Restaurante::orderByDesc('puntuacion')->get();
            @endphp

            @foreach($mejoresRestaurantes as $mejorRestaurante)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        @if (Str::startsWith($mejorRestaurante->imagen, 'imagenes/'))
                            <img src="{{ asset($mejorRestaurante->imagen) }}" class="card-img-top" alt="Imagen del restaurante">
                        @elseif (Str::startsWith($mejorRestaurante->imagen, 'restaurantes/'))
                            <img src="{{ asset('storage/' . $mejorRestaurante->imagen) }}" class="card-img-top" alt="Imagen del restaurante">
                        @else
                            <div class="no-imagen-disponible text-center p-4">
                                <p class="mb-0">Imagen no disponible</p>
                                <p class="text-muted">Este restaurante aún no tiene imágenes disponibles</p>
                            </div>
                        @endif

                        <div class="card-body">
                            <a href="{{ route('restaurantes.perfil', ['slug' => $mejorRestaurante->slug]) }}" class="nombre-restaurante" style="text-decoration: none;">
                                <h5 class="card-title">{{ $mejorRestaurante->nombre }}</h5>
                            </a>                                               
                            <p class="card-text">{{ $mejorRestaurante->gastronomia }}</p>
                            <div class="custom-star-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $mejorRestaurante->puntuaciones->avg('puntuacion'))
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
