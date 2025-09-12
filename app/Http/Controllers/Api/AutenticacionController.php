<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AutenticacionController extends Controller
{
    /**
     * Iniciar sesión (Login)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function iniciarSesion(Request $request): JsonResponse
    {
        try {
            // Validar los datos de entrada
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos de inicio de sesión inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar el usuario por email
            $usuario = User::where('email', $request->email)->first();

            // Verificar si el usuario existe y la contraseña es correcta
            if (!$usuario || !Hash::check($request->password, $usuario->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas'
                ], 401);
            }

            // Crear token de acceso
            $token = $usuario->createToken('api-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'usuario' => [
                        'id' => $usuario->id,
                        'rut' => $usuario->rut,
                        'nombre' => $usuario->nombre,
                        'apellido' => $usuario->apellido,
                        'email' => $usuario->email,
                    ],
                    'token' => $token,
                    'tipo_token' => 'Bearer'
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor durante el inicio de sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión (Logout)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function cerrarSesion(Request $request): JsonResponse
    {
        try {
            // Revocar el token actual
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revocar todos los tokens del usuario
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function revocarTodosLosTokens(Request $request): JsonResponse
    {
        try {
            // Revocar todos los tokens del usuario
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Todos los tokens han sido revocados exitosamente'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al revocar tokens',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
