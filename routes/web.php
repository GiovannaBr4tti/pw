<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TarefasController;


/*
|--------------------------------------------------------------------------
| Rotas Web
|--------------------------------------------------------------------------
*/


// Home
Route::get('/', [HomeController::class, 'index']);



// Login
Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');




// Cadastro
Route::get('/cadastro', [AuthController::class, 'index'])
    ->name('cadastro');

Route::post('/cadastro', [AuthController::class, 'store']);




// Tarefas

Route::get('/tarefas', [TarefasController::class, 'index'])
    ->name('tarefas');

Route::post('/tarefas', [TarefasController::class, 'store'])
    ->name('tarefas.store');

Route::get('/tarefas/{id}', [TarefasController::class, 'show'])
    ->name('tarefas.show');

Route::put('/tarefas/{id}', [TarefasController::class, 'update'])
    ->name('tarefas.update');

Route::delete('/tarefas/{id}', [TarefasController::class, 'destroy'])
    ->name('tarefas.destroy');