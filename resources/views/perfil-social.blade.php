@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/mensajes.css') }}">

<div class="container mt-3">
    @auth
    @if(Auth::user()->id === $usuario->id)

    <div class="mt-3">
        <h3>Amigos</h3>
        <ul class="list-group">
            @forelse($amigos as $amigo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $amigo->usuario }}
                    <div class="btn-group" role="group">
                        <a href="{{ route('perfil.mensajes', ['amigoId' => $amigo->id]) }}" class="btn btn-primary btn-messages">Mensajes</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminarAmigoModal{{ $amigo->id }}">
                            Eliminar
                        </button>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#bloquearAmigoModal{{ $amigo->id }}">
                            Bloquear
                        </button>
                    </div>
                </li>

                <!-- Modal para confirmar la eliminación de un amigo -->
                <div class="modal fade" id="eliminarAmigoModal{{ $amigo->id }}" tabindex="-1" role="dialog" aria-labelledby="eliminarAmigoModalLabel{{ $amigo->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eliminarAmigoModalLabel{{ $amigo->id }}">Confirmar Eliminación</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                ¿Seguro que quieres eliminar a {{ $amigo->usuario }} de tu lista de amigos?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <form action="{{ route('perfil.eliminarAmigo', ['amigoId' => $amigo->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para confirmar el bloqueo de un amigo -->
                <div class="modal fade" id="bloquearAmigoModal{{ $amigo->id }}" tabindex="-1" role="dialog" aria-labelledby="bloquearAmigoModalLabel{{ $amigo->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bloquearAmigoModalLabel{{ $amigo->id }}">Confirmar Bloqueo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                ¿Seguro que quieres bloquear a {{ $amigo->usuario }}? Esto eliminará todos los mensajes y no podrán enviarte más solicitudes de amistad.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <form action="{{ route('perfil.bloquearAmigo', ['amigoId' => $amigo->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Bloquear</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <li class="list-group-item">No tienes amigos.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-3">
        <h3>Solicitudes Pendientes</h3>
        <ul class="list-group">
            @forelse($solicitudesPendientes as $solicitud)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $solicitud->usuario->usuario }} te ha enviado una solicitud de amistad.
                    <form action="{{ route('perfil.aceptarSolicitud', ['id' => $solicitud->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Aceptar</button>
                    </form>
                </li>
            @empty
                <li class="list-group-item">No tienes solicitudes pendientes.</li>
            @endforelse
        </ul>
    </div>

    @endif
    @endauth
</div>

@endsection
