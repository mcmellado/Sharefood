@extends('layouts.app')

@section('contenido')


<div class="container">
    <form action="{{ route('restaurantes.buscar') }}" method="GET">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Buscar restaurantes..." name="q">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Buscar</button>
            </div>
        </div>
    </form>
</div>

<div class="container mt-5">
    <h2>Características Destacadas</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Explora Restaurantes</h5>
                    <p class="card-text">Encuentra una amplia variedad de restaurantes según tus preferencias culinarias.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection