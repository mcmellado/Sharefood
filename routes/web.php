<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::view('/registro', 'register')->name('registro');
Route::view('/perfil', 'perfil')->name('perfil');

Route::post('/validar-registro', [LoginController::class, 'register'])->name('validar-registro');
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion');
