<!-- resources/views/panel_admin_usuarios.blade.php -->
@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <div class="container">
        <h2 class="mt-4 mb-4">Bienvenido al Panel de Administrador</h2>

        <div class="row">
            <div class="col-md-12">
                <h3>Usuarios</h3>
                
                <!-- Botón para ir al panel de administrador de restaurantes -->
                <a href="{{ route('admin.panel_admin_restaurante') }}" class="btn btn-primary">Ir al Panel de Restaurantes</a>


                @if(session('contrasena-cambiada'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('contrasena-cambiada') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('usuario-eliminado'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('usuario-eliminado') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('usuario-modificado'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('usuario-modificado') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Teléfono</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">{{ $user->id }}</td>
                                    <td class="text-center">{{ $user->usuario }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">{{ $user->telefono ?? 'No tiene' }}</td>
                                    <td class="text-center">
                                        <form method="post" action="{{ route('admin.validar', $user->id) }}" style="display:inline">
                                            @csrf
                                            @if ($user->validacion)
                                                <button type="submit" class="btn btn-success btn-sm">Validado</button>
                                            @else
                                                <button type="submit" class="btn btn-danger btn-sm">Invalidar</button>
                                            @endif
                                        </form>
                                        <form method="post" action="{{ route('admin.eliminar', $user->id) }}" style="display:inline">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                        <a href="{{ route('admin.usuarios.modificar', $user->id) }}" class="btn btn-primary btn-sm">Modificar</a>
                                        <a href="{{ route('admin.usuarios.cambiar-contrasena-admin', $user->id) }}" class="btn btn-warning btn-sm">Cambiar Contraseña</a>
                                        <a href="{{ route('admin.usuarios.ver-comentarios', $user->id) }}" class="btn btn-info btn-sm">Ver Comentarios</a>
                                        <a href="{{ route('admin.usuarios.ver-reservas', $user->id) }}" class="btn btn-info btn-sm">Ver Reservas</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
