@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/perfil_admin_restaurantes.css') }}">


    <div class="container">
        <h2 class="mt-4 mb-4">Bienvenido al Panel de Administrador de Restaurantes</h2>

        <div class="row">
            <div class="col-md-12">
                <h3>Restaurantes</h3>

                <div class="table-responsive table-scrollable">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Usuario</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($restaurantes as $restaurante)
                                <tr>
                                    <td class="text-center">{{ $restaurante->id }}</td>
                                    <td class="text-center">{{ $restaurante->nombre }}</td>
                                    <td class="text-center">{{ $restaurante->usuario->usuario ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.restaurantes.modificar', $restaurante->id) }}" class="btn btn-primary btn-sm">Modificar</a>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmarEliminar{{ $restaurante->id }}">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <div class="modal fade" id="confirmarEliminar{{ $restaurante->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminarLabel{{ $restaurante->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmarEliminarLabel{{ $restaurante->id }}">Confirmar eliminación</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Seguro que deseas eliminar el restaurante "{{ $restaurante->nombre }}"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <form method="post" action="{{ route('admin.restaurantes.eliminar', $restaurante->id) }}" style="display:inline">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                                    </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay restaurantes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('admin.panel_admin') }}" class="btn btn-primary btn-return-admin mt-3">Volver administrador de usuarios</a>
            </div>
        </div>
    </div>
@endsection
