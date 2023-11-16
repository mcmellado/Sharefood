<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestauranteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::view('/perfil', 'perfil')->name('perfil');
Route::view('/index', 'index')->name('index');



Route::post('/validar-registro', [RegisterController::class, 'register'])->name('validar-registro');
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
Route::post('/registro', [RegisterController::class, 'register'])->name('registro-post');
Route::get('/index', [RegisterController::class, 'index'])->name('index');
Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('registro');
Route::post('/registro', [RegisterController::class, 'register']);
Route::view('/index', 'index')->name('index');

Route::get('/restaurantes', [RestauranteController::class, 'index'])->name('restaurantes');
Route::get('/restaurantes/buscar', [RestauranteController::class, 'buscar'])->name('restaurantes.buscar');