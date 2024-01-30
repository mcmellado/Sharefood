@extends('layouts.app')

<title> Gestionar carta </title>

@section('contenido')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/gestionar_carta.css') }}">


<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-4">Gestionar Carta de {{ $restaurante->nombre }}:</h1>
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
                        <span>{{ $producto->nombre }} - {{ $producto->descripcion }} - {{ $producto->precio }} €</span>
                        <div class="btn-group">
                            <a href="{{ route('restaurantes.editar_producto', ['slug' => $restaurante->slug, 'id' => $producto->id]) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i>     
                            </a>
                            <button type="button" class="btn btn-danger" onclick="confirmarEliminar('{{ route('restaurantes.eliminar_producto', ['slug' => $restaurante->slug, 'id' => $producto->id]) }}')">
                                <i class="fas fa-trash-alt"></i> 
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">No hay productos en la carta.</li>
                @endforelse
            </ul>

            <div class="btn-group mt-3">
                <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => Auth::user()->usuario]) }}" class="btn btn-danger btn-manage">
                    <i class="fas fa-arrow-left"></i> 
                </a>
                <a href="{{ route('restaurantes.formulario_agregar_producto', ['slug' => $restaurante->slug]) }}" class="btn btn-success btn-add-product">
                    <i class="fas fa-plus"></i> Agregar Producto
                </a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/gestionar_carta.js') }}" defer></script>


@endsection
