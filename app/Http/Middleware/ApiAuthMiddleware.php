<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Verificar si tiene token de autorización
            $authHeader = $request->header('Authorization');
            
            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado. Token de acceso requerido.',
                    'error' => 'Token no proporcionado'
                ], 401);
            }

            // Continuar con la verificación de Sanctum
            return app(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class)
                ->handle($request, function ($request) use ($next) {
                    return app('auth')->guard('sanctum')->authenticate()
                        ? $next($request)
                        : response()->json([
                            'success' => false,
                            'message' => 'Token inválido o expirado.',
                            'error' => 'Invalid token'
                        ], 401);
                });
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de autenticación.',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
