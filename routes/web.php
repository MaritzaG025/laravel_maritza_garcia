<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UsuarioController;

// Ruta del index principal
Route::get('/', [HomeController::class, 'gestion']);

// Ruta para todas las vistas de empresa
Route::resource('/empresas', EmpresaController::class);
Route::get('empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

// Dado que en usuarios entra tambien las empresas se crearon más rutas que ayudan al redireccionamiento correcto
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/empresa', [UsuarioController::class, 'getUsersByCompany'])->name('usuarios.empresa');
Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::get('/usuarios/{empresaId}/usuario_id={usuarioId}', [UsuarioController::class, 'show'])->name('usuarios.show');
Route::get('/usuarios/{empresa_id}/{usuario_id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuarios/{id_usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::delete('/usuarios/{id_usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
