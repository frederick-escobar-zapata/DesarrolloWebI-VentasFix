<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controlador de autenticación web para VentasFix
 * 
 * Este controlador maneja toda la autenticación web usando Laravel Sanctum.
 * Incluye login, logout, registro y la gestión de sesiones web.
 * 
 * ¿Por qué Sanctum para web?
 * - Sanctum puede manejar tanto autenticación web (cookies) como API (tokens)
 * - Es más moderno y flexible que el sistema tradicional de Laravel
 * - Permite usar la misma configuración para web y mobile/API
 * - Mejor seguridad con tokens de autenticación
 * 
 * Funciones principales:
 * - mostrarLogin(): Página de login
 * - login(): Procesar el login del usuario
 * - mostrarRegistro(): Página de registro  
 * - registro(): Procesar el registro de nuevo usuario
 * - logout(): Cerrar sesión del usuario
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-13
 */
class AutenticacionController extends Controller
{
    /**
     * Muestra la página de login
     * 
     * Esta es la página principal donde los usuarios ingresarán sus credenciales.
     * Si el usuario ya está autenticado, lo redirige al dashboard.
     * 
     * IMPORTANTE: Esta función se ejecuta cuando:
     * 1. Alguien va directamente a /login
     * 2. Laravel redirige automáticamente aquí cuando detecta que alguien no está autenticado
     * 
     * @return View|RedirectResponse
     */
    public function mostrarLogin()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        // Esto evita que usuarios autenticados vean la página de login
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // ARREGLO: Usar nuestra vista personalizada en lugar de 'auth.login'
        // Nuestra vista está en: resources/views/vertical-menu-template-no-customizer/app-auth-login.blade.php
        return view('vertical-menu-template-no-customizer.app-auth-login');
    }

    /**
     * Procesa el login del usuario
     * 
     * CORRECCIÓN IMPORTANTE: Para aplicaciones web con Sanctum, usamos el guard 'web' tradicional
     * Sanctum funciona así en web:
     * 1. Usa el guard 'web' para login (con Auth::attempt)
     * 2. El middleware 'auth:sanctum' verifica las sesiones web normalmente
     * 3. NO se generan tokens, solo sesiones tradicionales de Laravel
     * 
     * @param Request $request Datos del formulario de login
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // PASO 1: Validar los datos del formulario con mensajes específicos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // PASO 2: Usar el guard 'web' tradicional para autenticar
        // Auth::guard('web')->attempt() SÍ existe y funciona perfectamente
        // Esto verifica las credenciales y crea una sesión web normal
        if (Auth::guard('web')->attempt($credentials)) {
            // PASO 3: Regenerar la sesión para prevenir ataques de fijación de sesión
            $request->session()->regenerate();

            // PASO 4: Guardar información adicional de la sesión
            // Guardamos el tiempo de login usando Carbon con zona horaria de Chile
            $loginTime = \Carbon\Carbon::now('America/Santiago');
            $request->session()->put('login_time', $loginTime->timestamp);
            $request->session()->put('user_ip', $request->ip());

            // PASO 5: Redirigir al dashboard con mensaje de éxito
            return redirect()->route('dashboard')
                ->with('success', '¡Bienvenido de vuelta, ' . Auth::user()->name . '!');
        }

        // PASO 5: Si las credenciales son incorrectas, regresar al login con error específico
        return back()
            ->withErrors([
                'email' => 'Las credenciales proporcionadas son incorrectas.',
            ])
            ->withInput($request->only('email'));
    }

    /**
     * Muestra la página de registro de nuevos usuarios
     * 
     * Permite que nuevos usuarios se registren en el sistema.
     * Si el usuario ya está autenticado, lo redirige al dashboard.
     * 
     * @return View|RedirectResponse
     */
    public function mostrarRegistro()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    /**
     * Procesa el registro de un nuevo usuario
     * 
     * Valida los datos, crea el usuario en la base de datos y lo autentica
     * automáticamente después del registro exitoso.
     * 
     * @param Request $request Datos del formulario de registro
     * @return RedirectResponse
     */
    public function registro(Request $request): RedirectResponse
    {
        // Validar todos los datos del nuevo usuario
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Autenticar automáticamente al usuario recién registrado
        Auth::login($user);

        // Redirigir al dashboard con mensaje de bienvenida
        return redirect()->route('dashboard')
            ->with('success', '¡Cuenta creada exitosamente! Bienvenido ' . $user->name . '.');
    }

    /**
     * Cierra la sesión del usuario actual
     * 
     * Invalida la sesión web actual y redirige al usuario a la página de login.
     * Usa el guard 'web' para ser consistente con el login.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Obtener el usuario antes de hacer logout
        $userName = Auth::user()->name ?? 'Usuario';

        // Cerrar sesión usando el guard 'web' (consistente con el login)
        Auth::guard('web')->logout();

        // Invalidar la sesión y regenerar el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir al login con mensaje de despedida
        return redirect()->route('login')
            ->with('success', '¡Hasta luego ' . $userName . '! Sesión cerrada exitosamente.');
    }
}