<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaApiController;
use App\Http\Controllers\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tarefas', [TarefaApiController::class,'index']);

Route::get('/tarefas/{id}', [TarefaApiController::class,'show']);

Route::post('/tarefas', [TarefaApiController::class,'store']);

Route::put('/tarefas/{id}', [TarefaApiController::class,'update']);

Route::delete('/tarefas/{id}', [TarefaApiController::class,'destroy']);



Route::get('/usuarios',
[UserApiController::class,'index']);

Route::get('/usuarios/{id}',
[UserApiController::class,'show']);

Route::post('/usuarios',
[UserApiController::class,'store']);

Route::put('/usuarios/{id}',
[UserApiController::class,'update']);

Route::delete('/usuarios/{id}',
[UserApiController::class,'destroy']);
