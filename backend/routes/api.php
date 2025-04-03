<?php

use App\Http\Controllers\ArquivosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\UsuariosFotosController;
use App\Http\Controllers\ProjetosController;
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\UserProjetoController;

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
Route::post('usuarios/usuarioCadastrar', [UsuariosController::class, 'usuarioCadastrar']); // rota não atenticada
Route::middleware('auth:api')->get('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {

    // Rotas para o login
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');

    Route::prefix('usuarios')->group(function () {
        Route::post('/criar', [UsuariosController::class, 'criar']);
        Route::get('/consultar/{id}', [UsuariosController::class, 'consultar']); // consultar individualmente
        Route::get('/listar', [UsuariosController::class, 'listar']); // listar todos
        Route::delete('/deletar/{id}', [UsuariosController::class, 'deletar']); // deleta um usuario
        Route::put('/editar/{id}', [UsuariosController::class, 'editar']); // cedita um usuario
        Route::patch('/editarUmaInformacao/{id}', [UsuariosController::class, 'editarUmaInforacao']); // cedita um usuario
        Route::post('/filtrar', [UsuariosController::class, 'filtrar']); // filtra todos
    });

    // Rotas para as fotos dos usuários
    Route::prefix('fotos')->group(function () {
        Route::post('/salvarFoto', [UsuariosFotosController::class, 'salvarFoto']);
    });

    // Rotas para os projetos
    Route::prefix('projetos')->group(function () {
        Route::post('/criar', [ProjetosController::class, 'criar']);
        Route::get('/consultar/{id}', [ProjetosController::class, 'consultar']); // consultar individualmente
        Route::get('/listar', [ProjetosController::class, 'listar']); // listar todos
        Route::delete('/deletar/{id}', [ProjetosController::class, 'deletar']); // deleta um usuario
        Route::patch('/editarUmaInformacao/{id}', [ProjetosController::class, 'editarUmaInforacao']); // cedita um usuario
        Route::post('/filtrar', [ProjetosController::class, 'filtrar']); // filtra todos
    });

    // Rotas para as terefas
    Route::prefix('tarefas')->group(function () {
        Route::post('/criar', [TarefasController::class, 'criar']);
        Route::get('/consultar/{id}', [TarefasController::class, 'consultar']); // consultar individualmente
        Route::get('/listar', [TarefasController::class, 'listar']); // listar todos
        Route::delete('/deletar/{id}', [TarefasController::class, 'deletar']); // deleta um usuario
        Route::patch('/editarUmaInformacao/{id}', [TarefasController::class, 'editarUmaInforacao']); // cedita um usuario
        Route::post('/filtrar', [TarefasController::class, 'filtrar']); // filtra todos
    });

    // Rotas para colabooradores
    Route::prefix('userProjeto')->group(function () {
        Route::post('/cadastrar', [UserProjetoController::class, 'cadastrar']);
        Route::delete('/deletar/{id}', [UserProjetoController::class, 'deletar']); // deleta um usuario
    });

    // Rotas para as arquivos
    Route::prefix('arquivos')->group(function () {
        Route::post('/salvarArquivo', [ArquivosController::class, 'salvarArquivo']);
        Route::get('/baixarArquivo/{id}', [ArquivosController::class, 'download']);
        Route::delete('/deletarArquivo/{id}', [ArquivosController::class, 'deletar']);
    });
});

Route::post('criar', [UsuariosController::class, 'criar']);
