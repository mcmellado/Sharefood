@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <!-- Mostrar "Perfil de Usuario" -->
                    <h1>Perfil de {{ $usuario->usuario }}</h1>

                    <!-- Mostrar imagen del usuario si tiene una -->
                    @if($usuario->imagen)
                        <img src="{{ asset($usuario->imagen) }}" alt="{{ $usuario->usuario }}" class="img-fluid mt-3">
                    @endif
                </div>
                <div class="col-md-8">
                    <!-- Mostrar información del usuario -->
                    <p>Email: {{ $usuario->email }}</p>
                    <p>Teléfono: {{ $usuario->telefono ?: 'No disponible' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
