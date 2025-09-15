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
class AuthController extends Controller
{
    /**
     * Muestra la página de login
     * 
     * Esta es la página principal donde los usuarios ingresarán sus credenciales.
     * Si el usuario ya está autenticado, lo redirige al dashboard.
     * 
     * @return View|RedirectResponse
     */
    public function mostrarLogin()
    {
        // Si el usuario ya está logueado, redirigir al dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login del usuario
     * 
     * Valida las credenciales y autentica al usuario usando Sanctum.
     * Si las credenciales son correctas, crea una sesión web y redirige al dashboard.
     * 
     * @param Request $request Datos del formulario de login
     * @return RedirectResponse
     */
    public function login(Request $request): RedirectResponse
    {
        // Validar los datos del formulario
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Intentar autenticar al usuario
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerar la sesión para prevenir ataques de fijación de sesión
            $request->session()->regenerate();

            // Redirigir al dashboard con mensaje de bienvenida
            return redirect()->route('dashboard')
                ->with('success', '¡Bienvenido ' . Auth::user()->name . '!');
        }

        // Si las credenciales son incorrectas, regresar con error
        throw ValidationException::withMessages([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ]);
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
     * Invalida la sesión actual y redirige al usuario a la página de login.
     * También limpia cualquier token de Sanctum asociado.
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        // Obtener el usuario antes de hacer logout
        $userName = Auth::user()->name ?? 'Usuario';

        // Cerrar sesión web
        Auth::logout();

        // Invalidar la sesión y regenerar el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir al login con mensaje de despedida
        return redirect()->route('login')
            ->with('success', '¡Hasta luego ' . $userName . '! Sesión cerrada exitosamente.');
    }
}