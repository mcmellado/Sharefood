<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\RegistroRestauranteController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

// Rutas para la autenticación de usuarios
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login'); // Mostrar formulario de inicio de sesión
Route::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro'); // Procesar registro de usuarios
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion'); // Mostrar y procesar formulario de inicio de sesión
Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); // Cerrar sesión de usuario
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro'); // Mostrar formulario de registro de usuario
Route::post('/registro', [RegisterController::class, 'register'])->name('registro-post'); // Procesar registro de usuarios

// Rutas para el registro y gestión de restaurantes
Route::get('/registro-restaurante', [RegistroRestauranteController::class, 'registroRestaurante'])->name('registro-restaurante');
Route::post('/registro-restaurante', [RegistroRestauranteController::class, 'validarRegistro'])->name('registrar.restaurante');
Route::post('/validar-registro-restaurante', [RegistroRestauranteController::class, 'validarRegistro'])->name('validar-registro-restaurante'); // Procesar registro de restaurantes

// Rutas relacionadas con la visualización y búsqueda de restaurantes
Route::view('/restaurantes', 'restaurantes')->name('restaurantes'); // Mostrar vista de restaurantes
Route::get('/restaurantes/buscar-sugerencias', [RestauranteController::class, 'buscarSugerencias'])->name('restaurantes.buscar-sugerencias');
Route::get('/restaurantes/buscar', [RestauranteController::class, 'buscar'])->name('restaurantes.buscar'); // Procesar búsqueda de restaurantes
Route::get('/index', [RegistroRestauranteController::class, 'index'])->name('index');
Route::get('/restaurantes/{slug}', [RestauranteController::class, 'mostrarPerfil'])->name('restaurantes.perfil');
Route::post('/restaurantes/comentar/{restauranteId}', [RestauranteController::class, 'comentar'])->name('restaurantes.comentar'); //comentar en restaurantes

Route::get('/{nombreUsuario}', [PerfilController::class, 'show'])->name('perfil');
Route::get('/{nombreUsuario}/modificar', [PerfilController::class, 'mostrarFormularioModificar'])->name('perfil.modificar');
Route::put('/perfil/modificar', [PerfilController::class, 'modificarPerfil'])->name('perfil.modificar.guardar');
Route::post('/perfil/modificar/subir-imagen', [PerfilController::class, 'subirImagen'])->name('perfil.modificar.subir-imagen');