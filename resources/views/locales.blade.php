@extends('layouts.app')
@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="{{ asset('css/locales.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-short" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container mt-5">
    <div class="card-body">
        <h1 class="mb-4 display-4">Mis Restaurantes:</h1>
        
        <div class="d-flex mb-2">
            <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary mr-2" style="width=100px;!important">
                <i class="fas fa-arrow-left"></i> 
            </a>
        
            <form action="{{ route('crear-nuevo-restaurante.formulario') }}" method="post">
                @csrf
                <button type="submit" class="btn btn-success mr-2">
                    <i class="fas fa-plus"></i> Crear Nuevo Restaurante
                </button>
            </form>
        </div>
        

        @if(count($restaurantes) > 0)
            <ul class="list-group">
                @foreach($restaurantes as $restaurante)
                    <li class="list-group-item">
                        <h5>{{ $restaurante->nombre }}</h5>
                        <p>Dirección: {{ $restaurante->direccion }}</p>
                        <p>Sitio web: {{ $restaurante->sitio_web ?? 'No disponible' }}</p>
                        <p>Teléfono: {{ $restaurante->telefono ?? 'No disponible' }}</p>
                        <a href="{{ route('restaurante.mis-restaurantes.modificar', ['slug' => $restaurante->slug]) }}" class="btn btn-info" title="Modificar Restaurante">
                            <i class="fas fa-pencil-alt"></i>
                        </a>

                        <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary" title="Ver Reservas">
                            <i class="far fa-calendar"></i>
                        </a>

                        <a href="{{ route('restaurantes.verComentarios', ['slug' => $restaurante->slug]) }}" class="btn btn-secondary" title="Ver Comentarios">
                            <i class="far fa-comments"></i>
                        </a>

                        <a href="{{ route('restaurantes.gestionar_carta', ['slug' => $restaurante->slug]) }}" class="btn btn-success" title="Gestionar Carta">
                            <i class="fas fa-book-open"></i>
                        </a>

                        <a href="{{ route('restaurantes.ver_pedidos', ['slug' => $restaurante->slug]) }}" class="btn btn-primary" title="Ver Pedidos">
                            <i class="fas fa-utensils"></i>
                        </a>
                        
                        @if(!$restaurante->tieneReservasFuturas())
                        <a href="{{ route('restaurantes.horarios.editar', ['slug' => $restaurante->slug]) }}" class="btn btn-warning" title="Modificar Horas">
                            <i class="fas fa-clock"></i>
                        </a>
                        @else
                        <button class="btn btn-warning" onclick="mostrarMensajeReservas()">
                            <i class="fas fa-clock"></i>
                        </button>
                        @endif
                    

                        @if(!$restaurante->tieneReservasFuturas())
                        <button class="btn btn-danger" onclick="confirmarBorrado('{{ $restaurante->slug }}')">
                            <i class="far fa-trash-alt"></i> 
                        </button>
                    @else
                        <button class="btn btn-danger" onclick="mostrarMensajeReservas()">
                            <i class="far fa-trash-alt"></i> 
                        </button>
                    @endif
                    
               
                        
                    </li>
                @endforeach
            </ul>
        @else
            <p class="norestaurantes">No tienes restaurantes registrados.</p>
        @endif
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>

function confirmarBorrado(slug) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Una vez borrado, no podrás recuperar este restaurante.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('restaurante.borrar', ['slug' => ':slug']) }}".replace(':slug', slug);
            form.style.display = 'none';

            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);

            var methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);

            form.submit();
        }
    });
}

function mostrarMensajeReservas() {
    Swal.fire({
        title: 'No puedes borrar o editar el restaurante',
        text: 'Tienes reservas pendientes para este restaurante.',
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Entendido'
    });
}


</script>

@endsection
