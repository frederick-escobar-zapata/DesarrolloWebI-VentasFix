<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;

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

// Rutas para el módulo de Usuarios
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);           // GET /api/usuarios  - listo ya probado con postman
    Route::post('/', [UsuarioController::class, 'store']);          // POST /api/usuarios - listo ya probado con postman
    Route::get('/{id}', [UsuarioController::class, 'show']);        // GET /api/usuarios/{id} - listo ya probado con postman
    Route::put('/{id}', [UsuarioController::class, 'update']);      // PUT /api/usuarios/{id} - listo ya probado con postman
    Route::delete('/{id}', [UsuarioController::class, 'destroy']);  // DELETE /api/usuarios/{id} -listo ya probado con postman
});