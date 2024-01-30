@extends('layouts.app')

<title> Bloqueados </title>


@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/perfil-social.css') }}">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-3">
    @auth
    @if(Auth::user()->id === $usuario->id)

    <div class="mt-3">
        <h3>Amigos:</h3>
        <ul class="list-group">
            @forelse($amigos as $amigo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $amigo->usuario }}
                    <div class="btn-group" role="group">
                        <a href="{{ route('perfil.mensajes', ['amigoId' => $amigo->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-envelope"></i></a>

                        <form id="formEliminarAmigo{{ $amigo->id }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm eliminar-amigo" data-amigo-id="{{ $amigo->id }}" onclick="confirmEliminarAmigo({{ $amigo->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>

                        <form id="formBloquearAmigo{{ $amigo->id }}" method="post" style="display:inline">
                            @csrf
                            <button type="button" class="btn btn-warning btn-sm bloquear-amigo" data-amigo-id="{{ $amigo->id }}" onclick="confirmBloquearAmigo({{ $amigo->id }})">
                                <i class="fas fa-ban"></i>
                            </button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="list-group-item">No tienes amigos.</li>
            @endforelse
        </ul>
    </div>
    <br>

    <div class="mt-3">
        <h3>Solicitudes Pendientes:</h3>
        <ul class="list-group">
            @forelse($solicitudesPendientes as $solicitud)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $solicitud->usuario->usuario }} te ha enviado una solicitud de amistad.
                    <div class="d-flex">
                        <form action="{{ route('perfil.aceptarSolicitud', ['id' => $solicitud->id]) }}" method="POST" class="mr-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                        </form>
                        <form action="{{ route('perfil.rechazarSolicitud', ['id' => $solicitud->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="list-group-item">No tienes solicitudes pendientes.</li>
            @endforelse
        </ul>
        <br>
        <form action="{{ route('perfil.bloqueos') }}" method="POST">
            @csrf
            <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> 
            </a>
            
            <button type="submit" class="btn btn-danger mr-2" style="width: 150px;">Ver Bloqueos</button>
        </form>
    </div>

    @endif
    @endauth
</div>

<script>
    function confirmEliminarAmigo(amigoId) {
        Swal.fire({
            title: 'Confirmar Eliminación',
            text: '¿Seguro que quieres eliminar a este amigo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.getElementById('formEliminarAmigo' + amigoId);
                form.action = "{{ route('perfil.eliminarAmigo', ['amigoId' => ':amigoId']) }}".replace(':amigoId', amigoId);
                form.submit();
            }
        });
    }

    function confirmBloquearAmigo(amigoId) {
        Swal.fire({
            title: 'Confirmar Bloqueo',
            text: '¿Seguro que quieres bloquear a este amigo?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, bloquear'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.getElementById('formBloquearAmigo' + amigoId);
                form.action = "{{ route('perfil.bloquearAmigo', ['amigoId' => ':amigoId']) }}".replace(':amigoId', amigoId);
                form.submit();
            }
        });
    }
    
</script>

@endsection
