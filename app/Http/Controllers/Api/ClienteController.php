<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClienteServicio;
use App\Rules\RutChileno;
use App\Rules\NoSoloEspacios;
use App\Rules\SoloTexto;
use App\Rules\TelefonoChileno;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    protected ClienteServicio $clienteServicio;

    public function __construct(ClienteServicio $clienteServicio)
    {
        $this->clienteServicio = $clienteServicio;
    }

    /**
     * Listar todos los clientes
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $clientes = $this->clienteServicio->listarTodos();
            
            return response()->json([
                'success' => true,
                'message' => 'Clientes obtenidos exitosamente',
                'data' => $clientes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener clientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo cliente
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validaciones mejoradas con RUT válido y sin espacios
            $validator = Validator::make($request->all(), [
                'rut_empresa' => [
                    'required',
                    'string',
                    'unique:clientes,rut_empresa',
                    'max:12',
                    new RutChileno(),
                    new NoSoloEspacios()
                ],
                'rubro' => [
                    'required',
                    'string',
                    'max:100',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'razon_social' => [
                    'required',
                    'string',
                    'max:255',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'telefono' => [
                    'required',
                    'string',
                    'max:20',
                    new NoSoloEspacios(),
                    new TelefonoChileno()
                ],
                'direccion' => [
                    'required',
                    'string',
                    new NoSoloEspacios()
                ],
                'nombre_contacto' => [
                    'required',
                    'string',
                    'max:255',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'email_contacto' => [
                    'required',
                    'email:rfc,dns',
                    'max:255',
                    new NoSoloEspacios()
                ],
            ], [
                // Mensajes personalizados en español
                'rut_empresa.required' => 'El RUT de la empresa es obligatorio.',
                'rut_empresa.unique' => 'Ya existe un cliente con este RUT.',
                'rut_empresa.max' => 'El RUT no puede tener más de 12 caracteres.',
                'rubro.required' => 'El rubro es obligatorio.',
                'rubro.max' => 'El rubro no puede tener más de 100 caracteres.',
                'razon_social.required' => 'La razón social es obligatoria.',
                'razon_social.max' => 'La razón social no puede tener más de 255 caracteres.',
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
                'direccion.required' => 'La dirección es obligatoria.',
                'nombre_contacto.required' => 'El nombre del contacto es obligatorio.',
                'nombre_contacto.max' => 'El nombre del contacto no puede tener más de 255 caracteres.',
                'email_contacto.required' => 'El email del contacto es obligatorio.',
                'email_contacto.email' => 'El email del contacto debe tener un formato válido (ejemplo: contacto@empresa.cl).',
                'email_contacto.max' => 'El email del contacto no puede tener más de 255 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cliente = $this->clienteServicio->agregar($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente',
                'data' => $cliente
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un cliente por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $cliente = $this->clienteServicio->obtenerPorId((int)$id);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cliente obtenido exitosamente',
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un cliente por su ID
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validaciones mejoradas para actualización
            $validator = Validator::make($request->all(), [
                'rut_empresa' => [
                    'sometimes',
                    'required',
                    'string',
                    'unique:clientes,rut_empresa,' . $id,
                    'max:12',
                    new RutChileno(),
                    new NoSoloEspacios()
                ],
                'rubro' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:100',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'razon_social' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'telefono' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:20',
                    new NoSoloEspacios(),
                    new TelefonoChileno()
                ],
                'direccion' => [
                    'sometimes',
                    'required',
                    'string',
                    new NoSoloEspacios()
                ],
                'nombre_contacto' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    new NoSoloEspacios(),
                    new SoloTexto()
                ],
                'email_contacto' => [
                    'sometimes',
                    'required',
                    'email:rfc,dns',
                    'max:255',
                    new NoSoloEspacios()
                ],
            ], [
                // Mensajes personalizados en español para actualización
                'rut_empresa.required' => 'El RUT de la empresa no puede estar vacío.',
                'rut_empresa.unique' => 'Ya existe otro cliente con este RUT.',
                'rut_empresa.max' => 'El RUT no puede tener más de 12 caracteres.',
                'rubro.required' => 'El rubro no puede estar vacío.',
                'rubro.max' => 'El rubro no puede tener más de 100 caracteres.',
                'razon_social.required' => 'La razón social no puede estar vacía.',
                'razon_social.max' => 'La razón social no puede tener más de 255 caracteres.',
                'telefono.required' => 'El teléfono no puede estar vacío.',
                'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
                'direccion.required' => 'La dirección no puede estar vacía.',
                'nombre_contacto.required' => 'El nombre del contacto no puede estar vacío.',
                'nombre_contacto.max' => 'El nombre del contacto no puede tener más de 255 caracteres.',
                'email_contacto.required' => 'El email del contacto no puede estar vacío.',
                'email_contacto.email' => 'El email del contacto debe tener un formato válido (ejemplo: contacto@empresa.cl).',
                'email_contacto.max' => 'El email del contacto no puede tener más de 255 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $cliente = $this->clienteServicio->actualizar((int)$id, $request->all());

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'data' => $cliente
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un cliente por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->clienteServicio->eliminar((int)$id);

            if (!$eliminado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cliente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
