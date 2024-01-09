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

                <!-- Botón que activa el modal de desbloqueo -->
                <button class="btn btn-danger btn-sm float-right" onclick="openModal('{{ $usuarioBloqueado->usuario }}', '{{ route('perfil.desbloquearUsuario', ['usuarioId' => $usuarioBloqueado->id]) }}')">
                    Desbloquear
                </button>
            </li>
        @empty
            <li class="list-group-item">No tienes usuarios bloqueados.</li>
        @endforelse
    </ul>
    <a href="{{ route('perfil.social', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger mt-3">Volver atrás</a>

    <!-- Modal para confirmar desbloqueo -->
    <div class="modal fade" id="desbloquearModal" tabindex="-1" role="dialog" aria-labelledby="desbloquearModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="desbloquearModalLabel">Desbloquear Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="usuarioDesbloquear"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    
                    <!-- Formulario para enviar la solicitud de desbloqueo -->
                    <form id="desbloquearForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-danger">Desbloquear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(nombreUsuario, urlDesbloqueo) {
            $('#desbloquearForm').attr('action', urlDesbloqueo);
            $('#usuarioDesbloquear').html('¿Estás seguro de desbloquear a ' + nombreUsuario + '?');
            $('#desbloquearModal').modal('show');
        }

        // Añade este código para cerrar el modal cuando se hace clic en "Cancelar"
        $('#desbloquearModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset'); // Reinicia el formulario dentro del modal
        });
    </script>
</div>

@endsection
