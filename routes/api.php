<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\ExamsController as ApiExamsController;
use App\Http\Controllers\Api\GroupsController as ApiGroupsController;
use App\Http\Controllers\Api\PackagesController as ApiPackagesController;
use App\Http\Controllers\Api\PdfController as ApiPdfController;

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

Route::prefix('v1')->group(function () {

    // Dashboard
    Route::get('/dashboard-counts', [ApiDashboardController::class, 'index']); // ............ Obtenção de lista de métricas gerais do sistema

    // Rotas para grupos
    Route::get('/groups', [ApiGroupsController::class, 'index']); // ......................... Obtenção de lista de grupos cadastrados

    // Rotas para exames
    Route::get('/exams', [ApiExamsController::class, 'index']); // ........................... Obtenção de lista de exames cadastrados
    Route::post('/exams', [ApiExamsController::class, 'store']); // .......................... Criação de um novo exame
    Route::put('/exams/{exam}', [ApiExamsController::class, 'update']); // ................... Atualização de exames

    // Rotas para pacotes de exames
    Route::get('/packages', [ApiPackagesController::class, 'index']); // ..................... Obtenção de lista de pacotes de exames cadastrados
    Route::post('/packages', [ApiPackagesController::class, 'store']); // .................... Criação de um novo pacotes de exames
    Route::put('/packages/{package}', [ApiPackagesController::class, 'update']); // .......... Atualização de pacotes de exames

    // Export PDF
    Route::post('/pdf/export', [ApiPdfController::class, 'export']); // .......................... Criação de um novo exame
});
