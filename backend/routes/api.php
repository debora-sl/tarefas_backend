<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuariosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('login', [UsuariosController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me'])->middleware('auth:api');

    Route::prefix('usuarios')->group(function () {
        Route::post('/criar', [UsuariosController::class, 'criar']);
        Route::get('/consultar/{id}', [UsuariosController::class, 'consultar']); // consultar individualmente
        Route::get('/listar', [UsuariosController::class, 'listar']); // listar todos
        Route::delete('/deletar/{id}', [UsuariosController::class, 'deletar']); // deleta um usuario
        Route::put('/editar/{id}', [UsuariosController::class, 'editar']); // cedita um usuario
        Route::patch('/editarUmaInformacao/{id}', [UsuariosController::class, 'editarUmaInforacao']); // cedita um usuario
        Route::post('/filtrar', [UsuariosController::class, 'filtrar']); // filtra todos
    });
});

Route::post('criar', [UsuariosController::class, 'criar']);
