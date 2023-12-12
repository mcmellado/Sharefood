<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\RegistroRestauranteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ReservaController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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
Route::delete('/restaurantes/comentarios/{comentarioId}', [RestauranteController::class, 'eliminarComentario'])->name('restaurantes.eliminarComentario');

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

// Rutas para el panel de administrador
Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel_admin');
    Route::get('/admin/panel', [AdminController::class, 'index'])->name('admin.panel_admin');
    Route::post('/admin/validar/{id}', [AdminController::class, 'validar'])->name('admin.validar');
    Route::delete('/admin/eliminar/{id}', [AdminController::class, 'eliminar'])->name('admin.eliminar');
    Route::get('/admin/usuarios/{usuarioId}/modificar', [AdminController::class, 'mostrarFormularioModificar'])->name('admin.usuarios.modificar');
    Route::put('/admin/usuarios/{usuarioId}/modificar', [AdminController::class, 'modificarPerfil'])->name('admin.usuarios.modificar.guardar');
    Route::get('/admin/usuarios/{usuarioId}/cambiar-contrasena', [AdminController::class, 'mostrarFormularioCambiarContrasena'])->name('admin.usuarios.cambiar-contrasena-admin');
    Route::put('/admin/usuarios/{usuarioId}/cambiar-contrasena', [AdminController::class, 'cambiarContrasena'])->name('admin.usuarios.cambiar-contrasena-admin.guardar');
    Route::get('/admin/usuarios/{usuarioId}/comentarios', [AdminController::class, 'verComentarios'])->name('admin.usuarios.ver-comentarios');
    Route::get('/admin/usuarios/{usuarioId}/reservas', [AdminController::class, 'verReservas'])->name('admin.usuarios.ver-reservas');
    Route::delete('/admin/comentarios/{comentarioId}/eliminar', [AdminController::class, 'eliminarComentario'])->name('admin.comentarios.eliminar');
    Route::get('/admin/usuarios/{usuarioId}/comentarios', [AdminController::class, 'verComentarios'])->name('admin.usuarios.ver-comentarios');
    Route::delete('/admin/reservas/{reservaId}/cancelar', [AdminController::class, 'cancelarReserva'])->name('admin.reservas.cancelar');
    Route::put('/admin/reservas/modificar/{reservaId}', [AdminController::class, 'modificarReserva'])->name('admin.reservas.modificar-reserva');
    Route::put('admin/reservas/{reservaId}', [AdminController::class, 'modificarReserva'])->name('admin.reservas.guardar-modificacion');
    Route::put('/admin/reservas/modificar/{reservaId}', [AdminController::class, 'modificarReserva'])->name('admin.reservas.modificar-reserva');

});




