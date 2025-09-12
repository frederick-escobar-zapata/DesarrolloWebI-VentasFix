<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ClienteController;

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

// Rutas para el módulo de Productos
Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index']);          // GET /api/productos - listo ya probado con postman
    Route::post('/', [ProductoController::class, 'store']);         // POST /api/productos -    listo ya probado con postman
    Route::get('/{id}', [ProductoController::class, 'show']);       // GET /api/productos/{id} -        listo ya probado con postman
    Route::put('/{id}', [ProductoController::class, 'update']);     // PUT /api/productos/{id} - listo ya probado con postman
    Route::delete('/{id}', [ProductoController::class, 'destroy']); // DELETE /api/productos/{id} - listo ya probado con postman
});

// Rutas para el módulo de Clientes
Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'index']);           // GET /api/clientes - listo ya probado con postman
    Route::post('/', [ClienteController::class, 'store']);          // POST /api/clientes - listo ya probado con postman
    Route::get('/{id}', [ClienteController::class, 'show']);        // GET /api/clientes/{id}  - listo ya probado con postman
    Route::put('/{id}', [ClienteController::class, 'update']);      // PUT /api/clientes/{id} - listo ya probado con postman
    Route::delete('/{id}', [ClienteController::class, 'destroy']);  // DELETE /api/clientes/{id}
});