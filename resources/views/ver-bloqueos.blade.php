@extends('layouts.app')

<title> Bloqueados </title>

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/bloqueos.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container mt-3">
    <h3>Bloqueos</h3>
    <ul class="list-group">
        @forelse($bloqueos as $bloqueo)
            @php
                $usuarioBloqueado = \App\Models\User::find($bloqueo->usuario_bloqueado_id);
            @endphp
            <li class="list-group-item" id="usuarioBloqueado{{ $usuarioBloqueado->id }}">
                {{ $usuarioBloqueado->usuario }}

                <form id="desbloquearForm{{ $usuarioBloqueado->id }}" data-usuario-id="{{ $usuarioBloqueado->id }}" action="{{ route('perfil.desbloquearUsuario', ['usuarioId' => $usuarioBloqueado->id]) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="button" class="btn btn-danger btn-sm btn-desbloquear" onclick="confirmDesbloquearUsuario({{ $usuarioBloqueado->id }})">
                        <i class="fas fa-unlock"></i> 
                    </button>
                </form>
            </li>
        @empty
            <li class="list-group-item">No tienes usuarios bloqueados.</li>
        @endforelse
        <a href="{{ route('perfil.social', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> 
        </a>
    </ul>
</div>

<script>
    function confirmDesbloquearUsuario(usuarioId) {
        Swal.fire({
            title: 'Confirmar Desbloqueo',
            text: '¿Seguro que quieres desbloquear a este usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, desbloquear'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = document.getElementById('desbloquearForm' + usuarioId);

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                })
                .then(response => response.json())
                .then(data => {
                    var elementoLista = document.getElementById('usuarioBloqueado' + usuarioId);
                    if (elementoLista) {
                        elementoLista.remove(); 
                    }
                    var lista = document.querySelector('.list-group');
                    if (!lista.hasChildNodes()) {
                        var mensajeNoBloqueados = document.createElement('li');
                        mensajeNoBloqueados.className = 'list-group-item';
                        mensajeNoBloqueados.textContent = 'No hay usuarios bloqueados.';
                        lista.appendChild(mensajeNoBloqueados);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
</script>



@endsection
