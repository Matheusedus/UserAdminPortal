<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\DashboardController;

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

// Rotas protegidas para administradores autenticados
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);          // Listagem de usuários com filtros e paginação
    Route::put('/users/{user}', [UserController::class, 'update']);  // Atualização de dados de um usuário
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // Exclusão de usuário
    Route::get('/dashboard', [DashboardController::class, 'index']); // Métricas: total de usuários, por cidade e perfil
});

// Rotas públicas de autenticação
Route::post('/register', [AuthController::class, 'register']); // Cadastro de novo usuário
Route::post('/login', [AuthController::class, 'login']);       // Login e geração de token
Route::post('/forgot-password', [PasswordResetController::class, 'forgot']); // Solicitação de redefinição de senha
Route::post('/reset-password', [PasswordResetController::class, 'reset']);   // Redefinir senha com token