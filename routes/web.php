<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UsuarioWebController;
use App\Http\Controllers\Web\ProductoWebController;
use App\Http\Controllers\Web\ClienteWebController;
use App\Http\Controllers\Web\AutenticacionController;

/*
|--------------------------------------------------------------------------
RUTAS WEB DE VENTASFIX - SISTEMA DE AUTENTICACIÓN CON SESSION TIMEOUT
|--------------------------------------------------------------------------
|
ARCHIVO ACTUALIZADO: 14 de Septiembre 2025
IMPLEMENTACIÓN: Laravel Sanctum + Session Timeout Middleware

ESTRUCTURA ORGANIZADA:
| 1. Rutas públicas (login/logout) 
| 2. Rutas protegidas con autenticación + session timeout
|   - Dashboard
|   - Gestión de Usuarios
|   - Gestión de Productos  
|   - Gestión de Clientes
|
 SEGURIDAD:
| - Solo /login es accesible sin autenticación
| - Todas las demás rutas requieren login + verificación de timeout
| - Session timeout automático después de 15 minutos
*/

// ============================================================================
// 🌍 RUTAS PÚBLICAS (SIN AUTENTICACIÓN REQUERIDA)
// ============================================================================

// 📋 Vista previa del formulario de login - Solo para demo
Route::get('/login-preview', function () {
    return view('vertical-menu-template-no-customizer.app-auth-login');
})->name('login.preview');

// ============================================================================
// 🔐 RUTAS DE AUTENTICACIÓN
// ============================================================================

// 🔑 MOSTRAR FORMULARIO DE LOGIN
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');

// 🔑 PROCESAR LOGIN DEL USUARIO
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.post');

// 🚪 LOGOUT DEL USUARIO (requiere autenticación)
Route::post('/logout', [AutenticacionController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================================================
// 🔐 GRUPO DE RUTAS PROTEGIDAS CON AUTENTICACIÓN Y SESSION TIMEOUT
// ============================================================================
//
// IMPORTANTE: Todas estas rutas requieren:
// 1. Usuario autenticado (middleware 'auth')  
// 2. Verificación de session timeout (middleware 'check.session.timeout')
//
Route::middleware(['auth', 'check.session.timeout'])->group(function () {

    // ========================================================================
    // 🏠 DASHBOARD PRINCIPAL
    // ========================================================================
    
    // Dashboard principal
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ruta alternativa al dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    })->name('dashboard.alt');

    // ========================================================================
    // 👥 GESTIÓN DE USUARIOS - TODAS LAS RUTAS PROTEGIDAS
    // ========================================================================
    
    // 📋 LISTAR TODOS LOS USUARIOS - Lista usuarios de la base de datos
    // URL: /usuarios → Tabla con todos los usuarios registrados en el sistema
    Route::get('/usuarios', [UsuarioWebController::class, 'index'])->name('usuarios.index');
    
    // 🔍 BUSCAR USUARIO POR ID ESPECÍFICO - Función especial protegida
    // URL: /usuarios/buscar-por-id → Formulario para buscar usuario por su ID
    Route::get('/usuarios/buscar-por-id', [UsuarioWebController::class, 'listById'])->name('usuarios.list-by-id');
    
    // ➕ CREAR NUEVOS USUARIOS - Ambas rutas protegidas
    // URL: GET /usuarios/crear → Muestra formulario para nuevo usuario
    // URL: POST /usuarios/crear → Procesa y guarda el nuevo usuario creado
    Route::get('/usuarios/crear', [UsuarioWebController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios/crear', [UsuarioWebController::class, 'store'])->name('usuarios.store');
    
    // 🔄 ACTUALIZAR USUARIO POR ID ESPECÍFICO - Función especial protegida
    // URL: /usuarios/actualizar-por-id → Permite buscar y actualizar usuario por su ID
    // SOPORTA: GET (mostrar formulario) y POST (procesar actualización)
    Route::match(['GET', 'POST'], '/usuarios/actualizar-por-id', [UsuarioWebController::class, 'actualizarPorId'])->name('usuarios.actualizar-por-id');
    
    // ❌ ELIMINAR USUARIO POR ID ESPECÍFICO - Función con confirmación protegida  
    // URL: /usuarios/eliminar-por-id → Busca usuario y muestra confirmación
    // SOPORTA: GET (mostrar confirmación) y POST (buscar usuario a eliminar)
    Route::match(['GET', 'POST'], '/usuarios/eliminar-por-id', [UsuarioWebController::class, 'eliminarPorId'])->name('usuarios.eliminar-por-id');
    
    // 💥 PROCESAMIENTO FINAL DE ELIMINACIÓN - Acción definitiva protegida
    // URL: DELETE /usuarios/eliminar-por-id/procesar → Elimina definitivamente el usuario
    // REQUIERE: Confirmación previa del usuario antes de ejecutar
    Route::delete('/usuarios/eliminar-por-id/procesar', [UsuarioWebController::class, 'procesarEliminarPorId'])->name('usuarios.eliminar-por-id.procesar');
    
    // 👀 VISUALIZAR Y EDITAR USUARIOS - Operaciones básicas protegidas  
    // URL: /usuarios/{id} → Muestra detalles completos del usuario seleccionado
    // URL: /usuarios/{id}/editar → Formulario pre-cargado para editar usuario
    Route::get('/usuarios/{id}', [UsuarioWebController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{id}/editar', [UsuarioWebController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioWebController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioWebController::class, 'destroy'])->name('usuarios.destroy');

    // ========================================================================
    // 📦 GESTIÓN DE PRODUCTOS
    // ========================================================================
    
    // Listar todos los productos
    Route::get('/productos', [ProductoWebController::class, 'index'])->name('productos.index');
    
    // Buscar producto por ID específico
    Route::get('/productos/buscar-por-id', [ProductoWebController::class, 'listById'])->name('productos.list-by-id');
    
    // Actualizar producto por ID específico
    Route::match(['GET', 'POST'], '/productos/actualizar-por-id', [ProductoWebController::class, 'actualizarPorId'])->name('productos.actualizar-por-id');
    
    // Eliminar producto por ID específico
    Route::match(['GET', 'POST'], '/productos/eliminar-por-id', [ProductoWebController::class, 'eliminarPorId'])->name('productos.eliminar-por-id');
    
    // Procesar eliminación de producto
    Route::delete('/productos/eliminar-por-id/procesar', [ProductoWebController::class, 'procesarEliminarPorId'])->name('productos.eliminar-por-id.procesar');
    
    // CRUD tradicional de productos
    Route::get('/productos/crear', [ProductoWebController::class, 'create'])->name('productos.create');
    Route::post('/productos/crear', [ProductoWebController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}', [ProductoWebController::class, 'show'])->name('productos.show');
    Route::get('/productos/{id}/editar', [ProductoWebController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{id}', [ProductoWebController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{id}', [ProductoWebController::class, 'destroy'])->name('productos.destroy');

    // ========================================================================
    // 👤 GESTIÓN DE CLIENTES - CARTERA COMPLETA PROTEGIDA
    // ========================================================================
    
    // 📋 CARTERA COMPLETA - Lista todos los clientes registrados  
    // URL: /clientes → Tabla con todos los clientes de la empresa
    Route::get('/clientes', [ClienteWebController::class, 'index'])->name('clientes.index');
    
    // 🔍 BUSCAR CLIENTE POR ID - Búsqueda específica protegida
    // URL: /clientes/buscar-por-id → Formulario para localizar cliente por su ID  
    Route::get('/clientes/buscar-por-id', [ClienteWebController::class, 'mostrarListarPorId'])->name('clientes.list-by-id');
    
    // ➕ REGISTRAR NUEVO CLIENTE - Formulario de registro protegido
    // URL: /clientes/crear → Formulario para agregar cliente a la cartera
    Route::get('/clientes/crear', [ClienteWebController::class, 'mostrarCrear'])->name('clientes.create');
    
    // 💾 GUARDAR CLIENTE NUEVO - Procesamiento POST protegido
    // URL: POST /clientes → Recibe datos del formulario y registra el cliente
    Route::post('/clientes', [ClienteWebController::class, 'crear'])->name('clientes.store');
    
    // 🔄 ACTUALIZAR CLIENTE POR ID - Operaciones de modificación protegidas
    // URL: GET /clientes/actualizar-por-id → Formulario de búsqueda para actualizar
    // URL: POST /clientes/actualizar-por-id → Procesa la actualización del cliente  
    Route::get('/clientes/actualizar-por-id', [ClienteWebController::class, 'mostrarActualizarPorId'])->name('clientes.actualizar-por-id');
    Route::post('/clientes/actualizar-por-id', [ClienteWebController::class, 'actualizarPorId'])->name('clientes.actualizar-por-id.post');
    
    // ❌ ELIMINAR CLIENTE POR ID - Eliminación con confirmación protegida
    // URL: /clientes/eliminar-por-id → Busca cliente y solicita confirmación
    // SOPORTA: GET (mostrar confirmación) y POST (buscar cliente a eliminar)
    Route::match(['GET', 'POST'], '/clientes/eliminar-por-id', [ClienteWebController::class, 'eliminarPorId'])->name('clientes.eliminar-por-id');
    
    // 💥 PROCESAMIENTO FINAL DE ELIMINACIÓN - Acción definitiva protegida
    // URL: DELETE /clientes/eliminar-por-id/procesar → Elimina definitivamente el cliente
    // REQUIERE: Confirmación previa antes de proceder con la eliminación
    Route::delete('/clientes/eliminar-por-id/procesar', [ClienteWebController::class, 'procesarEliminarPorId'])->name('clientes.eliminar-por-id.procesar');

});

// ============================================================================
// 📋 FIN DEL ARCHIVO DE RUTAS
// ============================================================================