@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/perfil-restaurante.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


<div class="container mt-5">
    <h2>Perfil del Restaurante</h2>

    <div>
        <h3>{{ $restaurante->nombre }}</h3>
        <p><strong>Dirección:</strong> {{ $restaurante->direccion }}</p>
        <p><strong>Sitio Web:</strong> {{ $restaurante->sitio_web }}</p>
        <p><strong>Teléfono:</strong> {{ $restaurante->telefono }}</p>
        <p><strong>Gastronomía:</strong> {{ $restaurante->gastronomia }}</p>
        <p><strong>Puntuación:</strong> {{ $restaurante->puntuacion }} &#9733;</p>
    </div>

</div>

@endsection
