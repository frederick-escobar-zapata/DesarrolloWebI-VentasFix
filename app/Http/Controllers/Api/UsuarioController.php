<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UsuarioServicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    protected UsuarioServicio $usuarioServicio;

    public function __construct(UsuarioServicio $usuarioServicio)
    {
        $this->usuarioServicio = $usuarioServicio;
    }

    /**
     * Listar todos los usuarios
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $usuarios = $this->usuarioServicio->listarTodos();
            
            // Verificar si no existen registros
            if (empty($usuarios) || count($usuarios) === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No existen registros de usuarios',
                    'data' => []
                ], 200);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Usuarios obtenidos exitosamente',
                'data' => $usuarios
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo usuario
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Verificar si se envió email en el POST (no permitido)
            if ($request->has('email')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => [
                        'email' => ['El email será generado automáticamente con el formato nombre.apellido@ventasfix.cl. No debe ser enviado en la petición.']
                    ]
                ], 422);
            }

            // Validaciones básicas de formato (sin email, se genera automáticamente)
            $validator = Validator::make($request->all(), [
                'rut' => ['required', 'string', 'regex:/^[0-9]{7,8}-[0-9Kk]{1}$/', 'unique:users,rut'],
                'nombre' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/', 'not_regex:/.*[0-9].*/'],
                'apellido' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/', 'not_regex:/.*[0-9].*/'],
                'password' => 'required|string|min:8'
            ], [
                'rut.required' => 'El RUT es obligatorio.',
                'rut.string' => 'El RUT debe ser una cadena de texto.',
                'rut.regex' => 'El RUT debe tener el formato correcto (7-8 dígitos, guión y dígito verificador). Ejemplo: 12345678-9',
                'rut.unique' => 'Este RUT ya está registrado.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.not_regex' => 'El nombre no puede estar vacío, contener solo espacios o incluir números.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.string' => 'El apellido debe ser una cadena de texto.',
                'apellido.not_regex' => 'El apellido no puede estar vacío, contener solo espacios o incluir números.',
                'apellido.max' => 'El apellido no puede tener más de 255 caracteres.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validación del RUT chileno (dígito verificador)
            $rut = $request->input('rut');
            if (!$this->validarRutChileno($rut)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => [
                        'rut' => ['El dígito verificador del RUT no es válido.']
                    ]
                ], 422);
            }

            // Generar email automáticamente con formato nombre.apellido@ventasfix.cl
            $nombre = strtolower(trim($request->input('nombre')));
            $apellido = strtolower(trim($request->input('apellido')));
            $email = $this->generarEmail($nombre, $apellido);

            // Verificar que el email generado no exista ya
            if (\App\Models\User::where('email', $email)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => [
                        'email' => ['Ya existe un usuario con el email generado: ' . $email]
                    ]
                ], 422);
            }

            // Agregar el email generado a los datos
            $datosUsuario = $request->all();
            $datosUsuario['email'] = $email;

            $usuario = $this->usuarioServicio->agregar($datosUsuario);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => $usuario
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un usuario por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $usuario = $this->usuarioServicio->obtenerPorId((int)$id);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario obtenido exitosamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un usuario por su ID
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Verificar si se envió email en el POST (no permitido)
            if ($request->has('email')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => [
                        'email' => ['El email será generado automáticamente con el formato nombre.apellido@ventasfix.cl. No debe ser enviado en la petición.']
                    ]
                ], 422);
            }

            // Validaciones básicas de formato (sin email, se genera automáticamente)
            $validator = Validator::make($request->all(), [
                'rut' => ['sometimes', 'string', 'regex:/^[0-9]{7,8}-[0-9Kk]{1}$/', 'unique:users,rut,' . $id],
                'nombre' => ['sometimes', 'required', 'string', 'max:255', 'not_regex:/^\s*$/', 'not_regex:/.*[0-9].*/'],
                'apellido' => ['sometimes', 'required', 'string', 'max:255', 'not_regex:/^\s*$/', 'not_regex:/.*[0-9].*/'],
                'password' => ['sometimes', 'required', 'string', 'min:8', 'not_regex:/^\s*$/']
            ], [
                'rut.string' => 'El RUT debe ser una cadena de texto.',
                'rut.regex' => 'El RUT debe tener el formato correcto (7-8 dígitos, guión y dígito verificador). Ejemplo: 12345678-9',
                'rut.unique' => 'Este RUT ya está registrado.',
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.not_regex' => 'El nombre no puede estar vacío, contener solo espacios o incluir números.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.string' => 'El apellido debe ser una cadena de texto.',
                'apellido.not_regex' => 'El apellido no puede estar vacío, contener solo espacios o incluir números.',
                'apellido.max' => 'El apellido no puede tener más de 255 caracteres.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de texto.',
                'password.not_regex' => 'La contraseña no puede estar vacía o contener solo espacios.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validación del RUT chileno (dígito verificador) si se envió
            if ($request->has('rut')) {
                $rut = $request->input('rut');
                if (!$this->validarRutChileno($rut)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errores de validación',
                        'errors' => [
                            'rut' => ['El dígito verificador del RUT no es válido.']
                        ]
                    ], 422);
                }
            }

            // Preparar datos para actualizar
            $datosActualizacion = $request->all();

            // Generar email automáticamente si se actualiza nombre o apellido
            if ($request->has('nombre') || $request->has('apellido')) {
                // Obtener el usuario actual para tener los datos completos
                $usuarioActual = $this->usuarioServicio->obtenerPorId((int)$id);
                if (!$usuarioActual) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Usuario no encontrado'
                    ], 404);
                }

                // Usar el nuevo nombre/apellido o mantener el actual
                $nombre = $request->input('nombre', $usuarioActual->nombre);
                $apellido = $request->input('apellido', $usuarioActual->apellido);
                
                $nombre = strtolower(trim($nombre));
                $apellido = strtolower(trim($apellido));
                $email = $this->generarEmail($nombre, $apellido);

                // Verificar que el email generado no exista ya (excluyendo el usuario actual)
                if (\App\Models\User::where('email', $email)->where('id', '!=', $id)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Errores de validación',
                        'errors' => [
                            'email' => ['Ya existe otro usuario con el email generado: ' . $email]
                        ]
                    ], 422);
                }

                $datosActualizacion['email'] = $email;
            }

            $usuario = $this->usuarioServicio->actualizar((int)$id, $datosActualizacion);

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un usuario por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->usuarioServicio->eliminar((int)$id);

            if (!$eliminado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validar RUT chileno usando algoritmo módulo 11
     * 
     * @param string $rut
     * @return bool
     */
    private function validarRutChileno(string $rut): bool
    {
        // Separar número y dígito verificador
        $partes = explode('-', $rut);
        if (count($partes) !== 2) {
            return false;
        }

        $numero = $partes[0];
        $digitoVerificador = strtoupper($partes[1]);

        // Calcular dígito verificador esperado usando módulo 11
        $suma = 0;
        $multiplicador = 2;
        
        // Procesar dígitos de derecha a izquierda
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += (int)$numero[$i] * $multiplicador;
            $multiplicador = $multiplicador === 7 ? 2 : $multiplicador + 1;
        }

        $resto = $suma % 11;
        $digitoEsperado = 11 - $resto;

        // Convertir resultado a formato de dígito verificador
        if ($digitoEsperado === 11) {
            $digitoEsperado = '0';
        } elseif ($digitoEsperado === 10) {
            $digitoEsperado = 'K';
        } else {
            $digitoEsperado = (string)$digitoEsperado;
        }

        return $digitoVerificador === $digitoEsperado;
    }

    /**
     * Generar email automáticamente con formato nombre.apellido@ventasfix.cl
     * 
     * @param string $nombre
     * @param string $apellido
     * @return string
     */
    private function generarEmail(string $nombre, string $apellido): string
    {
        // Limpiar y normalizar nombre y apellido
        $nombre = $this->limpiarTextoParaEmail($nombre);
        $apellido = $this->limpiarTextoParaEmail($apellido);
        
        return $nombre . '.' . $apellido . '@ventasfix.cl';
    }

    /**
     * Limpiar texto para usar en email (remover acentos, espacios, caracteres especiales)
     * 
     * @param string $texto
     * @return string
     */
    private function limpiarTextoParaEmail(string $texto): string
    {
        // Convertir a minúsculas
        $texto = strtolower($texto);
        
        // Remover acentos y caracteres especiales
        $acentos = ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'];
        $sinAcentos = ['a', 'e', 'i', 'o', 'u', 'n', 'u'];
        $texto = str_replace($acentos, $sinAcentos, $texto);
        
        // Remover espacios y caracteres especiales, mantener solo letras y números
        $texto = preg_replace('/[^a-z0-9]/', '', $texto);
        
        return $texto;
    }
}
