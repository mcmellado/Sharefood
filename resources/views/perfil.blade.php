@extends('layouts.app')

@section('contenido')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">

    <script>
        // Agrega un evento al formulario para mostrar la alerta después de enviar la solicitud
        $('#form-solicitud').submit(function (e) {
            e.preventDefault();
            // Realiza la lógica para enviar la solicitud...
            // Muestra la alerta de solicitud enviada utilizando Bootstrap
            $('#solicitud-enviada-alert').fadeIn().delay(2000).fadeOut();
            // Aquí también puedes agregar lógica adicional o redireccionar si es necesario
        });
    </script>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center">
                <!-- Alerta para mostrar después de enviar la solicitud -->
                @if(session('success'))
                <div id="solicitud-enviada-alert" class="alert alert-success alert-dismissible fade show" style="display: none;">
                        {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                 </div>
                @endif
                <!-- Alerta para mostrar si ya existe una solicitud de amistad o son amigos -->
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mt-2">
                        {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if($usuario && $usuario->imagen)
                    <img src="{{ '/storage/' . $usuario->imagen }}" alt="{{ $usuario->usuario }}" class="img-fluid mt-3 img-perfil rounded-circle">
                @endif

                <h1 class="mt-3">{{ $usuario->usuario }}</h1>
                
                <table class="table mt-3">
                    <tbody>
                        <tr>
                            <th scope="row">Correo Electrónico</th>
                            <td>{{ $usuario->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Teléfono</th>
                            <td>{{ $usuario->telefono }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Biografía</th>
                            <td>{{ $usuario->biografia }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="mt-3">
                    @auth
                        @if(Auth::user()->id !== $usuario->id)
                            <form action="{{ route('perfil.enviarSolicitud', ['nombreUsuario' => $usuario->usuario]) }}" method="POST" id="form-solicitud">
                                @csrf
                                <button type="submit" class="btn btn-success btn-enviar-solicitud">Mandar solicitud de amistad</button>
                            </form>
                            <!-- Puedes añadir un botón para bloquear aquí -->
                        @endif
                    @endauth
                
                    @auth
                        @if(Auth::user()->id === $usuario->id)
                            <a href="{{ route('perfil.modificar', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-primary btn-modificar mr-2">Modificar Perfil</a>
                            <a href="{{ route('perfil.mis-restaurantes', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-dark btn-modificar mr-2">Mis Restaurantes</a>
                            <a href="{{ route('perfil.reservas', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-info btn-ver-reservas mr-2">Ver Reservas</a>
                            <a href="{{ route('perfil.social', ['nombreUsuario' => $usuario->usuario]) }}" class="btn btn-info btn-ver-reservas mr-2">Social</a>
                            <a href="{{ route('logout') }}" class="btn btn-danger btn-cerrar-sesion ml-2">Cerrar Sesión</a>
                        @endif
                    @endauth
                </div>
                

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection
