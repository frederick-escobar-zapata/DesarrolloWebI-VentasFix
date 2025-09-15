<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UsuarioWebController;
use App\Http\Controllers\Web\ProductoWebController;
use App\Http\Controllers\Web\ClienteWebController;
use App\Http\Controllers\Web\AutenticacionController;

/*
|--------------------------------------------------------------------------
RUTAS WEB DE VENTASFIX - SISTEMA DE AUTENTICACIÓN SANCTUM
|--------------------------------------------------------------------------
|
ARCHIVO ACTUALIZADO: 14 de Septiembre 2025
PROPÓSITO: Definir todas las rutas web del sistema VentasFix con autenticación
|
SISTEMA DE AUTENTICACIÓN IMPLEMENTADO:
Laravel Sanctum para autenticación web (sesiones, no tokens)
Middleware 'auth:sanctum' protege todas las rutas importantes
Redirección automática a /login cuando no estés autenticado
Sesión configurada para 15 minutos de inactividad
|
ESTRUCTURA ORGANIZADA:
| 1. Rutas protegidas del Dashboard (requieren login)
| 2. CRUD de Usuarios (todas protegidas)
| 3. CRUD de Productos (todas protegidas)  
| 4. CRUD de Clientes (todas protegidas)
| 5. Rutas públicas de autenticación (login/logout)
|
 SEGURIDAD:
| - Solo /login es accesible sin autenticación
| - Todas las demás rutas redirigen al login automáticamente
| - Logout requiere autenticación (lógico, solo usuarios logueados pueden hacer logout)
|
*/

// ============================================================================
// RUTAS PRINCIPALES - DASHBOARD (PROTEGIDAS CON AUTENTICACIÓN)
// ============================================================================
// 
// ESTAS RUTAS ESTÁN PROTEGIDAS: Solo usuarios autenticados pueden acceder
// 
// ¿CÓMO FUNCIONA EL MIDDLEWARE?
// 1. Usuario intenta ir a "/" sin estar logueado
// 2. Laravel intercepta la petición y ve middleware('auth:sanctum')
// 3. Sanctum verifica si hay una SESIÓN activa (no busca tokens)
// 4. Como no hay sesión → Laravel redirige automáticamente a /login
// 5. Usuario se loguea → Laravel crea sesión → usuario puede acceder

// 🔧 RUTA TEMPORAL PARA PRUEBAS (se puede quitar después)
Route::get('/login-preview', function () {
    return view('vertical-menu-template-no-customizer.app-auth-login');
})->name('login.preview');

// 🏠 PÁGINA PRINCIPAL PROTEGIDA (Dashboard)
// Esta es la página de inicio de VentasFix - solo usuarios autenticados
// Si intentas acceder sin login → automático redirect a /login
// Si ya estás logueado → ves el dashboard con estadísticas
Route::get('/', [DashboardController::class, 'index'])
    ->name('home')
    ->middleware('auth:sanctum');

// 📊 RUTA ALTERNATIVA AL DASHBOARD 
// Misma funcionalidad que '/' pero con URL más descriptiva
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth:sanctum');

// ============================================================================
// 👥 GESTIÓN DE USUARIOS - TODAS LAS RUTAS PROTEGIDAS
// ============================================================================
//
// SEGURIDAD IMPLEMENTADA: Cada ruta tiene middleware('auth:sanctum')
// 
// ¿QUÉ SIGNIFICA ESTO EN LA PRÁCTICA?
// - Si intentas ir a /usuarios sin login → automático redirect a /login
// - Solo usuarios autenticados pueden ver, crear, editar o eliminar usuarios
// - Sanctum verifica la SESIÓN (no tokens) antes de cada petición
//
// FUNCIONALIDADES DISPONIBLES:
// Ver lista de usuarios
// Buscar usuario por ID
// Crear nuevos usuarios  
// Actualizar usuarios existentes
// Eliminar usuarios con confirmación
// Ver detalles individuales de cada usuario

// LISTAR TODOS LOS USUARIOS - Solo usuarios autenticados
// URL: /usuarios → Muestra tabla con todos los usuarios registrados
Route::get('/usuarios', [UsuarioWebController::class, 'index'])
    ->name('usuarios.index')
    ->middleware('auth:sanctum');

// 🔍 BUSCAR USUARIO POR ID ESPECÍFICO - Función especial protegida
// URL: /usuarios/buscar-por-id → Formulario para buscar usuario por su ID
Route::get('/usuarios/buscar-por-id', [UsuarioWebController::class, 'listById'])
    ->name('usuarios.list-by-id')
    ->middleware('auth:sanctum');

// 🔄 ACTUALIZAR USUARIO POR ID ESPECÍFICO - Función especial protegida
// URL: /usuarios/actualizar-por-id → Permite buscar y actualizar usuario por su ID
// SOPORTA: GET (mostrar formulario) y POST (procesar actualización)
Route::match(['GET', 'POST'], '/usuarios/actualizar-por-id', [UsuarioWebController::class, 'actualizarPorId'])
    ->name('usuarios.actualizar-por-id')
    ->middleware('auth:sanctum');

// ❌ ELIMINAR USUARIO POR ID ESPECÍFICO - Función con confirmación protegida  
// URL: /usuarios/eliminar-por-id → Busca usuario y muestra confirmación
// SOPORTA: GET (mostrar confirmación) y POST (buscar usuario a eliminar)
Route::match(['GET', 'POST'], '/usuarios/eliminar-por-id', [UsuarioWebController::class, 'eliminarPorId'])
    ->name('usuarios.eliminar-por-id')
    ->middleware('auth:sanctum');

// 💥 PROCESAMIENTO FINAL DE ELIMINACIÓN - Acción definitiva protegida
// URL: DELETE /usuarios/eliminar-por-id/procesar → Elimina definitivamente el usuario
// REQUIERE: Confirmación previa del usuario antes de ejecutar
Route::delete('/usuarios/eliminar-por-id/procesar', [UsuarioWebController::class, 'procesarEliminarPorId'])
    ->name('usuarios.eliminar-por-id.procesar')
    ->middleware('auth:sanctum');

// ➕ CREAR NUEVOS USUARIOS - Ambas rutas protegidas
// URL: GET /usuarios/crear → Muestra formulario para nuevo usuario
// URL: POST /usuarios/crear → Procesa y guarda el nuevo usuario creado
Route::get('/usuarios/crear', [UsuarioWebController::class, 'create'])
    ->name('usuarios.create')
    ->middleware('auth:sanctum');
    
Route::post('/usuarios/crear', [UsuarioWebController::class, 'store'])
    ->name('usuarios.store')
    ->middleware('auth:sanctum');

// 👀 VISUALIZAR Y EDITAR USUARIOS - Operaciones básicas protegidas  
// URL: /usuarios/{id} → Muestra detalles completos del usuario seleccionado
// URL: /usuarios/{id}/editar → Formulario pre-cargado para editar usuario
Route::get('/usuarios/{id}', [UsuarioWebController::class, 'show'])
    ->name('usuarios.show')
    ->middleware('auth:sanctum');
    
Route::get('/usuarios/{id}/editar', [UsuarioWebController::class, 'edit'])
    ->name('usuarios.edit')
    ->middleware('auth:sanctum');

// ============================================================================
// 📦 GESTIÓN DE PRODUCTOS - INVENTARIO COMPLETO PROTEGIDO
// ============================================================================
//
// 🔒 MISMA SEGURIDAD QUE USUARIOS: middleware('auth:sanctum') en TODAS las rutas
//
// ¿CÓMO FUNCIONA LA PROTECCIÓN?
// - Sin sesión activa → redirect automático a /login
// - Con sesión válida → acceso completo a inventario
// - Sanctum verifica cada petición contra la sesión del usuario
//
// 🎯 FUNCIONALIDADES DE INVENTARIO:
// ✅ Ver catálogo completo de productos
// ✅ Buscar producto específico por ID
// ✅ Agregar nuevos productos al inventario
// ✅ Actualizar información de productos  
// ✅ Eliminar productos con confirmación
// ✅ Ver detalles individuales completos

// 📋 CATÁLOGO COMPLETO - Lista todos los productos del inventario
// URL: /productos → Tabla con todos los productos registrados
Route::get('/productos', [ProductoWebController::class, 'index'])
    ->name('productos.index')
    ->middleware('auth:sanctum');

// 🔍 BUSCAR PRODUCTO POR ID - Búsqueda específica protegida
// URL: /productos/buscar-por-id → Formulario para localizar producto por ID
Route::get('/productos/buscar-por-id', [ProductoWebController::class, 'listById'])
    ->name('productos.list-by-id')
    ->middleware('auth:sanctum');

// 🔄 ACTUALIZAR PRODUCTO POR ID - Modificación específica protegida
// URL: /productos/actualizar-por-id → Busca y actualiza producto específico
// SOPORTA: GET (formulario de búsqueda) y POST (procesar actualización)
Route::match(['GET', 'POST'], '/productos/actualizar-por-id', [ProductoWebController::class, 'actualizarPorId'])
    ->name('productos.actualizar-por-id')
    ->middleware('auth:sanctum');

// ❌ ELIMINAR PRODUCTO POR ID - Eliminación con confirmación protegida
// URL: /productos/eliminar-por-id → Busca producto y solicita confirmación  
// SOPORTA: GET (mostrar confirmación) y POST (buscar producto a eliminar)
Route::match(['GET', 'POST'], '/productos/eliminar-por-id', [ProductoWebController::class, 'eliminarPorId'])
    ->name('productos.eliminar-por-id')
    ->middleware('auth:sanctum');

// 💥 PROCESAMIENTO FINAL DE ELIMINACIÓN - Acción definitiva protegida  
// URL: DELETE /productos/eliminar-por-id/procesar → Elimina definitivamente el producto
// REQUIERE: Confirmación previa antes de ejecutar la eliminación
Route::delete('/productos/eliminar-por-id/procesar', [ProductoWebController::class, 'procesarEliminarPorId'])
    ->name('productos.eliminar-por-id.procesar')
    ->middleware('auth:sanctum');

// ➕ AGREGAR NUEVO PRODUCTO - Formulario de creación protegido
// URL: /productos/crear → Formulario para registrar nuevo producto en inventario
Route::get('/productos/crear', [ProductoWebController::class, 'create'])
    ->name('productos.create')
    ->middleware('auth:sanctum');

// 💾 GUARDAR PRODUCTO NUEVO - Procesamiento POST protegido
// URL: POST /productos → Recibe datos del formulario y crea el producto
Route::post('/productos', [ProductoWebController::class, 'store'])
    ->name('productos.store')
    ->middleware('auth:sanctum');

// 👀 VER DETALLES DEL PRODUCTO - Información completa protegida
// URL: /productos/{id} → Muestra toda la información del producto seleccionado
Route::get('/productos/{id}', [ProductoWebController::class, 'show'])
    ->name('productos.show')
    ->middleware('auth:sanctum');

// ✏️ EDITAR PRODUCTO EXISTENTE - Formulario pre-cargado protegido
// URL: /productos/{id}/editar → Formulario con datos actuales para modificar producto
Route::get('/productos/{id}/editar', [ProductoWebController::class, 'edit'])
    ->name('productos.edit')
    ->middleware('auth:sanctum');

// 🔄 ACTUALIZAR PRODUCTO - Procesamiento PUT protegido
// URL: PUT /productos/{id} → Guarda los cambios del formulario de edición  
Route::put('/productos/{id}', [ProductoWebController::class, 'update'])
    ->name('productos.update')
    ->middleware('auth:sanctum');

// 🗑️ ELIMINAR PRODUCTO - Eliminación directa protegida
// URL: DELETE /productos/{id} → Elimina producto del inventario
Route::delete('/productos/{id}', [ProductoWebController::class, 'destroy'])
    ->name('productos.destroy')
    ->middleware('auth:sanctum');

// ============================================================================
// 👥 GESTIÓN DE CLIENTES - CARTERA COMPLETA PROTEGIDA
// ============================================================================
//
// 🔒 SEGURIDAD CONSISTENTE: middleware('auth:sanctum') en TODAS las rutas
//
// 📌 RECORDATORIO IMPORTANTE:
// - Sanctum en aplicaciones WEB usa SESIONES (no tokens API)
// - Sin login activo → redirect automático a /login
// - Con sesión válida → acceso completo a cartera de clientes
//
// 🎯 GESTIÓN COMPLETA DE CLIENTES:
// ✅ Ver cartera completa de clientes
// ✅ Buscar cliente específico por ID
// ✅ Registrar nuevos clientes  
// ✅ Actualizar información de clientes
// ✅ Eliminar clientes con confirmación
// ✅ Ver historial y detalles individuales

// 📋 CARTERA COMPLETA - Lista todos los clientes registrados  
// URL: /clientes → Tabla con todos los clientes de la empresa
Route::get('/clientes', [ClienteWebController::class, 'index'])
    ->name('clientes.index')
    ->middleware('auth:sanctum');

// 🔍 BUSCAR CLIENTE POR ID - Búsqueda específica protegida
// URL: /clientes/buscar-por-id → Formulario para localizar cliente por su ID  
Route::get('/clientes/buscar-por-id', [ClienteWebController::class, 'mostrarListarPorId'])
    ->name('clientes.list-by-id')
    ->middleware('auth:sanctum');

// ➕ REGISTRAR NUEVO CLIENTE - Formulario de registro protegido
// URL: /clientes/crear → Formulario para agregar cliente a la cartera
Route::get('/clientes/crear', [ClienteWebController::class, 'mostrarCrear'])
    ->name('clientes.create')
    ->middleware('auth:sanctum');

// 💾 GUARDAR CLIENTE NUEVO - Procesamiento POST protegido
// URL: POST /clientes → Recibe datos del formulario y registra el cliente
Route::post('/clientes', [ClienteWebController::class, 'crear'])
    ->name('clientes.store')
    ->middleware('auth:sanctum');

// 🔄 ACTUALIZAR CLIENTE POR ID - Operaciones de modificación protegidas
// URL: GET /clientes/actualizar-por-id → Formulario de búsqueda para actualizar
// URL: POST /clientes/actualizar-por-id → Procesa la actualización del cliente  
Route::get('/clientes/actualizar-por-id', [ClienteWebController::class, 'mostrarActualizarPorId'])
    ->name('clientes.actualizar-por-id')
    ->middleware('auth:sanctum');
    
Route::post('/clientes/actualizar-por-id', [ClienteWebController::class, 'actualizarPorId'])
    ->name('clientes.actualizar-por-id.post')
    ->middleware('auth:sanctum');

// ❌ ELIMINAR CLIENTE POR ID - Eliminación con confirmación protegida
// URL: /clientes/eliminar-por-id → Busca cliente y solicita confirmación
// SOPORTA: GET (mostrar confirmación) y POST (buscar cliente a eliminar)
Route::match(['GET', 'POST'], '/clientes/eliminar-por-id', [ClienteWebController::class, 'eliminarPorId'])
    ->name('clientes.eliminar-por-id')
    ->middleware('auth:sanctum');

// 💥 PROCESAMIENTO FINAL DE ELIMINACIÓN - Acción definitiva protegida
// URL: DELETE /clientes/eliminar-por-id/procesar → Elimina definitivamente el cliente
// REQUIERE: Confirmación previa antes de proceder con la eliminación
Route::delete('/clientes/eliminar-por-id/procesar', [ClienteWebController::class, 'procesarEliminarPorId'])
    ->name('clientes.eliminar-por-id.procesar')
    ->middleware('auth:sanctum');

// ============================================================================
// 🔓 AUTENTICACIÓN WEB - ÚNICAS RUTAS PÚBLICAS (SIN MIDDLEWARE)
// ============================================================================
//
// 🚨 IMPORTANTE: Estas son las ÚNICAS rutas que NO requieren autenticación
//
// ¿POR QUÉ ESTÁN SIN MIDDLEWARE?
// - Los usuarios necesitan poder acceder al login SIN estar logueados
// - El logout debe funcionar aún si la sesión está expirando  
// - Sin estas rutas públicas, nadie podría iniciar sesión inicialmente
//
// 🎯 FUNCIONES PÚBLICAS DISPONIBLES:
// ✅ Acceder al formulario de login (/login)
// ✅ Procesar el login (POST /login)  
// ✅ Cerrar sesión y limpiar datos (/logout)
//
// 🔒 NOTA CRÍTICA: TODO lo demás requiere autenticación obligatoria

// 🔑 FORMULARIO DE LOGIN - Página pública accesible sin autenticación
// URL: /login → Muestra el formulario de inicio de sesión  
// FUNCIÓN: Permite a usuarios sin sesión poder loguearse al sistema
// Cualquier persona puede acceder sin estar logueada

// MOSTRAR FORMULARIO DE LOGIN - RUTA PÚBLICA
// Esta es la única página que ven los usuarios NO autenticados
// Cuando alguien intenta acceder a una ruta protegida → Laravel los redirige aquí
Route::get('/login', [AutenticacionController::class, 'mostrarLogin'])->name('login');

// PROCESAR LOGIN - RUTA PÚBLICA (obvio, necesitas poder hacer login)
// Aquí es donde Sanctum verifica las credenciales y CREA la SESIÓN
// Si las credenciales son correctas → Laravel crea una sesión
// Si son incorrectas → regresa al formulario con errores
Route::post('/login', [AutenticacionController::class, 'login'])->name('login.post');

// PROCESAR LOGOUT - ESTA SÍ PODRÍA ESTAR PROTEGIDA (solo usuarios logueados pueden hacer logout)
// Destruye la SESIÓN y redirige al login
Route::post('/logout', [AutenticacionController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

// RUTA TEMPORAL PARA VER LA VISTA - SE PUEDE QUITAR DESPUÉS
Route::get('/login-preview', function () {
    return view('vertical-menu-template-no-customizer.app-auth-login');
})->name('login.preview');
