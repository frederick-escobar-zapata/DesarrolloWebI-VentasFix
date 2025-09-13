<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UsuarioWebController;
use App\Http\Controllers\Web\ProductoWebController;

/*
|--------------------------------------------------------------------------
| Rutas Web de VentasFix
|--------------------------------------------------------------------------
|
| Este archivo contiene todas las rutas web de la aplicación VentasFix.
| Las rutas están organizadas por funcionalidad y utilizan controladores
| específicos para manejar la lógica de cada sección.
|
| Estructura:
| - Rutas principales (Dashboard)
| - CRUD de Usuarios con funciones especiales
| - CRUD de Productos (básico)
| - Rutas de autenticación (pendientes)
| - Recursos adicionales (pendientes)
|
*/

// ============================================================================
// RUTAS PRINCIPALES - DASHBOARD
// ============================================================================
// Página principal del sistema - redirige al dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');

// Dashboard principal con estadísticas y resumen
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============================================================================
// GESTIÓN DE USUARIOS - CRUD COMPLETO CON FUNCIONES ESPECIALES
// ============================================================================
// Lista completa de usuarios en tabla
Route::get('/usuarios', [UsuarioWebController::class, 'index'])->name('usuarios.index');

// Búsqueda específica de usuario por ID (función especial)
Route::get('/usuarios/buscar-por-id', [UsuarioWebController::class, 'listById'])->name('usuarios.list-by-id');

// Actualización de usuario por ID específico (GET para mostrar, POST para procesar)
Route::match(['GET', 'POST'], '/usuarios/actualizar-por-id', [UsuarioWebController::class, 'actualizarPorId'])->name('usuarios.actualizar-por-id');

// Eliminación de usuario por ID específico (GET para mostrar, POST para buscar)
Route::match(['GET', 'POST'], '/usuarios/eliminar-por-id', [UsuarioWebController::class, 'eliminarPorId'])->name('usuarios.eliminar-por-id');

// Procesamiento final de eliminación con confirmación (DELETE para seguridad)
Route::delete('/usuarios/eliminar-por-id/procesar', [UsuarioWebController::class, 'procesarEliminarPorId'])->name('usuarios.eliminar-por-id.procesar');

// Creación de nuevos usuarios (GET para formulario, POST para guardar)
Route::get('/usuarios/crear', [UsuarioWebController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios/crear', [UsuarioWebController::class, 'store'])->name('usuarios.store');

// Visualización de detalles de un usuario específico
Route::get('/usuarios/{id}', [UsuarioWebController::class, 'show'])->name('usuarios.show');

// Edición de usuario existente por ID directo
Route::get('/usuarios/{id}/editar', [UsuarioWebController::class, 'edit'])->name('usuarios.edit');

// ============================================================================
// GESTIÓN DE PRODUCTOS - CRUD BÁSICO + FUNCIONES ESPECIALES
// ============================================================================
// Lista de todos los productos
Route::get('/productos', [ProductoWebController::class, 'index'])->name('productos.index');

// Búsqueda específica de producto por ID (función especial)
Route::get('/productos/buscar-por-id', [ProductoWebController::class, 'listById'])->name('productos.list-by-id');

// Actualización de producto por ID específico (GET para mostrar, POST para procesar)
Route::match(['GET', 'POST'], '/productos/actualizar-por-id', [ProductoWebController::class, 'actualizarPorId'])->name('productos.actualizar-por-id');

// Eliminación de producto por ID específico (GET para mostrar, POST para buscar)
Route::match(['GET', 'POST'], '/productos/eliminar-por-id', [ProductoWebController::class, 'eliminarPorId'])->name('productos.eliminar-por-id');

// Procesamiento final de eliminación con confirmación (DELETE para seguridad)
Route::delete('/productos/eliminar-por-id/procesar', [ProductoWebController::class, 'procesarEliminarPorId'])->name('productos.eliminar-por-id.procesar');

// Formulario para crear nuevo producto
Route::get('/productos/crear', [ProductoWebController::class, 'create'])->name('productos.create');

// Procesar creación de producto
Route::post('/productos', [ProductoWebController::class, 'store'])->name('productos.store');

// Mostrar detalles de un producto específico
Route::get('/productos/{id}', [ProductoWebController::class, 'show'])->name('productos.show');

// Formulario para editar producto existente
Route::get('/productos/{id}/editar', [ProductoWebController::class, 'edit'])->name('productos.edit');

// Procesar actualización de producto
Route::put('/productos/{id}', [ProductoWebController::class, 'update'])->name('productos.update');

// Eliminar producto (DELETE para seguridad)
Route::delete('/productos/{id}', [ProductoWebController::class, 'destroy'])->name('productos.destroy');

// ============================================================================
// AUTENTICACIÓN WEB - PENDIENTE DE IMPLEMENTACIÓN
// ============================================================================
// Estas rutas serán implementadas cuando se agregue el sistema de login web
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================================
// RECURSOS ADICIONALES - PENDIENTE DE IMPLEMENTACIÓN
// ============================================================================
// Rutas para otras entidades del sistema como clientes, ventas, etc.
// Route::resource('clientes', ClienteController::class);
// Route::resource('ventas', VentaController::class);
// Route::resource('reportes', ReporteController::class);
