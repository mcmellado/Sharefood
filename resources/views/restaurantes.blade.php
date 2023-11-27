@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/restaurante.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <h2 class="mt-5 mb-4">Nuestros restaurantes:</h2>
    <div class="list-container">
        <ul class="list-group">
            @php
                $mejoresRestaurantes = \App\Models\Restaurante::orderByDesc('puntuacion')->get();
            @endphp

            @foreach($mejoresRestaurantes as $mejorRestaurante)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('restaurantes.perfil', ['slug' => $mejorRestaurante->slug]) }}">
                            <span>{{ $mejorRestaurante->nombre }}</span>
                        </a>
                        <br>
                        <small>{{ $mejorRestaurante->gastronomia }}</small>
                    </div>
                    <span class="badge badge-success badge-pill">{{ $mejorRestaurante->puntuacion }} &#9733;</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@endsection
