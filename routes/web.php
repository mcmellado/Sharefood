<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\RegistroRestauranteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReservaController; // Agregado el controlador de Reserva
use Illuminate\Support\Facades\Route;

// Rutas para la autenticación de usuarios
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro');
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
Route::post('/registro', [RegisterController::class, 'register'])->name('registro-post');

// Rutas para el registro y gestión de restaurantes
Route::get('/registro-restaurante', [RegistroRestauranteController::class, 'registroRestaurante'])->name('registro-restaurante');
Route::post('/registro-restaurante', [RegistroRestauranteController::class, 'validarRegistro'])->name('registrar.restaurante');
Route::post('/validar-registro-restaurante', [RegistroRestauranteController::class, 'validarRegistro'])->name('validar-registro-restaurante');

// Rutas relacionadas con la visualización y búsqueda de restaurantes
Route::view('/restaurantes', 'restaurantes')->name('restaurantes');
Route::get('/restaurantes/buscar-sugerencias', [RestauranteController::class, 'buscarSugerencias'])->name('restaurantes.buscar-sugerencias');
Route::get('/restaurantes/buscar', [RestauranteController::class, 'buscar'])->name('restaurantes.buscar');
Route::get('/index', [RegistroRestauranteController::class, 'index'])->name('index');
Route::get('/restaurantes/{slug}', [RestauranteController::class, 'mostrarPerfil'])->name('restaurantes.perfil');
Route::post('/restaurantes/comentar/{restauranteId}', [RestauranteController::class, 'comentar'])->name('restaurantes.comentar');

Route::get('/{nombreUsuario}', [PerfilController::class, 'show'])->name('perfil');
Route::get('/{nombreUsuario}/modificar', [PerfilController::class, 'mostrarFormularioModificar'])->name('perfil.modificar');
Route::put('/perfil/modificar', [PerfilController::class, 'modificarPerfil'])->name('perfil.modificar.guardar');
Route::post('/perfil/modificar/subir-imagen', [PerfilController::class, 'subirImagen'])->name('perfil.modificar.subir-imagen');
Route::get('/perfil/cancelar-modificacion', [PerfilController::class, 'cancelarModificacion'])->name('perfil.modificar.cancelar');
Route::get('/perfil/reservas/{nombreUsuario}', [PerfilController::class, 'verReservas'])->name('perfil.reservas');

// Rutas para Reservas

Route::get('/restaurantes/{slug}/nueva-reserva', [ReservaController::class, 'nuevaReserva'])->name('restaurantes.nuevaReserva');
Route::post('/restaurantes/{slug}/guardar-reserva', [ReservaController::class, 'guardarReserva'])->name('restaurantes.guardarReserva');
Route::get('/restaurantes/{slug}/confirmar-reserva', [ReservaController::class, 'confirmarReserva'])->name('restaurantes.confirmarReserva');
