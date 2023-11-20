<?php
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\RegistroRestauranteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;

// Rutas para la autenticación de usuarios
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login'); // Mostrar formulario de inicio de sesión
Route::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro'); // Procesar registro de usuarios
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion'); // Mostrar y procesar formulario de inicio de sesión
Route::get('/logout', [LoginController::class, 'logout'])->name('logout'); // Cerrar sesión de usuario
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro'); // Mostrar formulario de registro de usuario
Route::post('/registro', [RegisterController::class, 'register'])->name('registro-post'); // Procesar registro de usuarios

// Rutas para el registro y gestión de restaurantes
Route::get('/registro-restaurante', [RegistroRestauranteController::class, 'index'])->name('registro-restaurante'); // Mostrar formulario de registro de restaurante
Route::post('/validar-registro-restaurante', [RegistroRestauranteController::class, 'validarRegistro'])->name('validar-registro-restaurante'); // Procesar registro de restaurantesRoute::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro');
Route::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro');

// Rutas relacionadas con la visualización y búsqueda de restaurantes
Route::view('/restaurantes', 'restaurantes.index')->name('restaurantes'); // Mostrar vista de restaurantes
Route::get('/restaurantes/buscar', [RestauranteController::class, 'buscar'])->name('restaurantes.buscar'); // Procesar búsqueda de restaurantes
Route::get('/index', [RegistroRestauranteController::class, 'index'])->name('index');
Route::get('/perfil', [PerfilController::class, 'mostrarPerfil'])->name('perfil');


