@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/bloqueos.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



<div class="container mt-3">
    <h3>Bloqueos</h3>
    <ul class="list-group">
        @forelse($bloqueos as $bloqueo)
            <li class="list-group-item">
                @php
                    $usuarioBloqueado = \App\Models\User::find($bloqueo->usuario_bloqueado_id);
                @endphp
                {{ $usuarioBloqueado->usuario }}

                <form id="desbloquearForm{{ $usuarioBloqueado->id }}" action="{{ route('perfil.desbloquearUsuario', ['usuarioId' => $usuarioBloqueado->id]) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="button" class="btn btn-danger btn-sm btn-desbloquear" onclick="confirmDesbloquearUsuario({{ $usuarioBloqueado->id }}, this)">
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
    function confirmDesbloquearUsuario(usuarioId, elementoBoton) {
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
                form.submit();
                var elementoLista = elementoBoton.closest('.list-group-item');
                if (elementoLista) {
                    elementoLista.style.display = 'none';
                }
            }
        });
    }
</script>

@endsection
