<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PetController;

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

/*
|--------------------------------------------------------------------------
| Pet API Routes
|--------------------------------------------------------------------------
*/
// Get form options (gêneros, espécies comuns) - deve vir antes do resource
Route::get('pets/options', [PetController::class, 'options']);

// CRUD routes for pets
Route::apiResource('pets', PetController::class);

// Rotas para CRUD de Pets
// Serão implementadas posteriormente