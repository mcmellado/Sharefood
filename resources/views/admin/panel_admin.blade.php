<!-- resources/views/admin/panel_admin.blade.php -->
@extends('layouts.app')

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


@section('contenido')
    <div class="container">
        <h2>Bienvenido al Panel de Administrador</h2>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Usuarios</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->usuario }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
