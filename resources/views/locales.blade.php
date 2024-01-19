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

        @if(count($restaurantes) > 0)
            <ul class="list-group">
                @foreach($restaurantes as $restaurante)
                    <li class="list-group-item">
                        <h5>{{ $restaurante->nombre }}</h5>
                        <p>Dirección: {{ $restaurante->direccion }}</p>
                        <p>Sitio web: {{ $restaurante->sitio_web ?? 'No disponible' }}</p>
                        <p>Teléfono: {{ $restaurante->telefono ?? 'No disponible' }}</p>
                        <a href="{{ route('restaurante.mis-restaurantes.modificar', ['slug' => $restaurante->slug]) }}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> </a>
                        <a href="{{ route('restaurantes.verReservas', ['slug' => $restaurante->slug]) }}" class="btn btn-primary btn-sm"><i class="far fa-calendar"></i> </a>
                        <a href="{{ route('restaurantes.verComentarios', ['slug' => $restaurante->slug]) }}" class="btn btn-secondary btn-sm"><i class="far fa-comments"></i> </a>
                        
                    
                        
                        <button class="btn btn-danger btn-sm" onclick="confirmarBorrado('{{ $restaurante->slug }}')">
                            <i class="far fa-trash-alt"></i> 
                        </button>
               
                        
                    </li>
                @endforeach
            </ul>
        @else
            <p>No tienes restaurantes registrados.</p>
        @endif
    </div>
</div>
<div class="btn-group">
    <a href="{{ route('perfil', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-primary mr-2">Volver al perfil</a>

    <form action="{{ route('crear-nuevo-restaurante.formulario') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-success mr-2">Crear Nuevo Restaurante</button>
    </form>
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
        confirmButtonText: 'Sí, borrar'
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
</script>

@endsection
