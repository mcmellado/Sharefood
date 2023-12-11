<!-- resources/views/admin/cambiar-contrasena-admin.blade.php -->
@extends('layouts.app')

@section('contenido')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h1>Cambiar Contraseña de {{ $usuario->usuario }}</h1>
            
            <form action="{{ route('admin.usuarios.cambiar-contrasena-admin.guardar', ['usuarioId' => $usuario->id]) }}" method="POST" 
                onsubmit="return validarContrasena(event)">
              @csrf
              @method('PUT')
          
              <div class="form-group">
                  <label for="password">Nueva Contraseña:</label>
                  <input type="password" name="password" id="password" class="form-control" required>
                  <small id="passwordHelp" class="form-text text-muted">
                      La contraseña debe tener al menos una letra y un número, no puede contener caracteres especiales y debe tener al menos 6 caracteres.
                  </small>
                  <div class="invalid-feedback" id="errorPasswordLength">
                      La contraseña debe tener al menos 6 caracteres.
                  </div>
                  <div class="invalid-feedback" id="errorPasswordPattern">
                      La contraseña debe tener al menos una letra y un número, y no puede contener caracteres especiales.
                  </div>
              </div>
          
              <div class="form-group">
                  <label for="password_confirmation">Confirmar Contraseña:</label>
                  <input type="password" name="password_confirmation" class="form-control" required>
              </div>
          
              <button type="submit" class="btn btn-success">Cambiar Contraseña</button>
          
              <a href="{{ route('admin.panel_admin') }}" class="btn btn-danger">Cancelar</a>
          
              <script>
                  function validarContrasena(event) {
                      var contrasena = document.getElementById('password').value;
                      var confirmarContrasena = document.getElementsByName('password_confirmation')[0].value;
          
                      if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(contrasena)) {
                          if (contrasena.length < 6) {
                              document.getElementById('errorPasswordLength').style.display = 'block';
                              document.getElementById('errorPasswordPattern').style.display = 'none';
                          } else {
                              document.getElementById('errorPasswordLength').style.display = 'none';
                              document.getElementById('errorPasswordPattern').style.display = 'block';
                          }
                          event.preventDefault();
                          return false;
                      } else {
                          document.getElementById('errorPasswordLength').style.display = 'none';
                          document.getElementById('errorPasswordPattern').style.display = 'none';
                      }
          
                      if (contrasena !== confirmarContrasena) {
                          alert('Las contraseñas no coinciden.');
                          event.preventDefault();
                          return false;
                      }
                      return true;
                  }
              </script>
          </form>
          
          
          
        </div>
    </div>
</div>

@endsection
