<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="./media/imagenes/favicon.ico"/>
    <link rel="stylesheet" href="{{ asset('css/registro.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Bienvenido a ShareFood - Registro de Restaurante</title>
</head>
<body>

    <header class="header">
        <nav class="container-sm">  
            <a href="#" class="text-decoration-none text-light me-3">Sobre nosotros</a>
            <a href="#" class="text-decoration-none text-light">Contacto</a>
        </nav>
    </header>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center">¡Registra tu restaurante en ShareFood!</h2>
                <p class="lead text-center">Crea tu cuenta y da a conocer tu restaurante.</p>

                <form method="POST" action="{{ route('validar-registro-restaurante') }}" id="registroRestauranteForm">
                    @csrf 
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del restaurante:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}">
                        @error('nombre')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                        @error('direccion')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Nombre de usuario:</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario') }}">
                        @error('usuario')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Registrar Restaurante</button>
                </form>     

                <div class="mt-3 text-center">
                    <p>¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
                    <p>¿Eres un usuario regular? <b><a href="{{ route('registro') }}" class="text-dark">Regístrate aquí</a></b></p>
                </div>

