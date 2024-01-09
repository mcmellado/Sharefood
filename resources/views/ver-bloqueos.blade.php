@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-3">
    <h3>Bloqueos</h3>
    <ul class="list-group">
        @forelse($bloqueos as $bloqueo)
            <li class="list-group-item">
                @php
                    $usuarioBloqueado = \App\Models\User::find($bloqueo->usuario_bloqueado_id);
                @endphp
                {{ $usuarioBloqueado->usuario }}
            </li>
        @empty
            <li class="list-group-item">No tienes usuarios bloqueados.</li>
        @endforelse
    </ul>
    <a href="{{ route('perfil.social', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger">Volver atrás</a>
</div>

@endsection
