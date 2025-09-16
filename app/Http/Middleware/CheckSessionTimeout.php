<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Middleware para verificar timeout de sesión personalizado
 * 
 * Este middleware verifica si la sesión ha expirado basándose en el tiempo de login
 * y la duración configurada. Si ha expirado, cierra la sesión y redirige al login.
 * 
 * ¿Por qué necesitamos este middleware?
 * - Laravel a veces no respeta estrictamente el session.lifetime
 * - Queremos un control más preciso de la expiración
 * - Necesitamos manejar la zona horaria chilena correctamente
 * 
 * @author VentasFix Team
 * @version 1.0
 */
class CheckSessionTimeout
{
    /**
     * Maneja una solicitud entrante
     * 
     * Verifica si la sesión ha expirado comparando:
     * 1. El tiempo actual
     * 2. El tiempo de login almacenado en la sesión
     * 3. La duración máxima configurada (15 minutos)
     * 
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Solo verificar si el usuario está autenticado
        if (Auth::check()) {
            
            // Obtener el tiempo de login desde la sesión
            $loginTime = Session::get('login_time');
            
            // Si no hay tiempo de login, establecer uno ahora (failsafe)
            if (!$loginTime) {
                Session::put('login_time', Carbon::now('America/Santiago')->timestamp);
                $loginTime = Session::get('login_time');
            }
            
            // Calcular si la sesión ha expirado
            $sessionLifetime = (int) config('session.lifetime', 15); // Asegurar que sea entero
            
            // Para producción: usar 15 minutos siempre
            // Para desarrollo: puedes cambiar a 1 minuto descomentando la línea de abajo
            // if (config('app.debug')) {
            //     $sessionLifetime = 1; // 1 minuto para testing
            // }
            
            $loginCarbon = Carbon::createFromTimestamp($loginTime, 'America/Santiago');
            $expirationTime = $loginCarbon->copy()->addMinutes($sessionLifetime); // Usar copy() para evitar mutación
            $currentTime = Carbon::now('America/Santiago');
            
            // Si la sesión ha expirado
            if ($currentTime->greaterThan($expirationTime)) {
                
                // Debug en desarrollo
                if (config('app.debug')) {
                    $originalLoginTime = Carbon::createFromTimestamp($loginTime, 'America/Santiago');
                    Log::info('Sesión expirada detectada', [
                        'login_time' => $originalLoginTime->format('Y-m-d H:i:s'),
                        'expiration_time' => $expirationTime->format('Y-m-d H:i:s'),
                        'current_time' => $currentTime->format('Y-m-d H:i:s'),
                        'session_lifetime' => $sessionLifetime
                    ]);
                }
                
                // Cerrar la sesión completamente usando el guard web tradicional
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirigir al login con mensaje de expiración
                return redirect()->route('login')
                    ->with('error', 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.')
                    ->with('session_expired', true);
            } else {
                // Debug: sesión válida
                if (config('app.debug')) {
                    $originalLoginTime = Carbon::createFromTimestamp($loginTime, 'America/Santiago');
                    Log::info('Sesión válida', [
                        'login_time' => $originalLoginTime->format('Y-m-d H:i:s'),
                        'expiration_time' => $expirationTime->format('Y-m-d H:i:s'),
                        'current_time' => $currentTime->format('Y-m-d H:i:s'),
                        'remaining_minutes' => $currentTime->diffInMinutes($expirationTime)
                    ]);
                }
            }
            
            // Actualizar la última actividad (opcional - para sesiones que se extienden con actividad)
            // Comentado porque queremos 15 minutos fijos desde el login
            // Session::put('last_activity', $currentTime->timestamp);
        }
        
        return $next($request);
    }
}