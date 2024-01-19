@extends('layouts.app')

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>

<form action="{{ route('restaurantes.eliminarComentario', ['comentarioId' => $comentario->id]) }}" method="POST" id="formEliminarComentario">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-danger" onclick="confirmarEliminacion()">
        <i class="fa fa-trash"></i> Eliminar
    </button>
</form>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Gestionar Carta de {{ $restaurante->nombre }}</h1>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <ul class="list-group">
                @forelse($productos as $producto)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $producto->nombre }} - {{ $producto->descripcion }} - {{ $producto->precio }} € </span>
                        <div class="btn-group">
                            <a href="{{ route('restaurantes.editar_producto', ['slug' => $restaurante->slug, 'id' => $producto->id]) }}" class="btn btn-primary">Editar</a>
                            <button type="button" class="btn btn-danger" onclick="confirmarEliminar('{{ route('restaurantes.eliminar_producto', ['slug' => $restaurante->slug, 'id' => $producto->id]) }}')">
                                Eliminar
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">No hay productos en la carta.</li>
                @endforelse
            </ul>
            <a href="{{ route('restaurantes.formulario_agregar_producto', ['slug' => $restaurante->slug]) }}" class="btn btn-success mt-3">Agregar Producto</a>
            <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger mt-3">Volver atrás</a>
        </div>
    </div>
</div>
<script>
    function confirmarEliminar(url) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

@endsection
