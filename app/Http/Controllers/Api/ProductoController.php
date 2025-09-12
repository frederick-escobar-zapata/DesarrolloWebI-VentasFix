<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    protected ProductoServicio $productoServicio;

    public function __construct(ProductoServicio $productoServicio)
    {
        $this->productoServicio = $productoServicio;
    }

    /**
     * Listar todos los productos
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $productos = $this->productoServicio->listarTodos();
            
            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos exitosamente',
                'data' => $productos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo producto
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validaciones
            $validator = Validator::make($request->all(), [
                'sku' => 'required|string|unique:productos,sku|max:50',
                'nombre' => 'required|string|max:255',
                'descripcion_corta' => 'required|string',
                'descripcion_larga' => 'required|string',
                'imagen_url' => 'nullable|string|url',
                'precio_neto' => 'required|numeric|min:0',
                'stock_actual' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'stock_bajo' => 'required|integer|min:0',
                'stock_alto' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $producto = $this->productoServicio->agregar($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener un producto por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $producto = $this->productoServicio->obtenerPorId((int)$id);

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto obtenido exitosamente',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar un producto por su ID
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // Validaciones
            $validator = Validator::make($request->all(), [
                'sku' => 'sometimes|string|unique:productos,sku,' . $id . '|max:50',
                'nombre' => 'sometimes|string|max:255',
                'descripcion_corta' => 'sometimes|string',
                'descripcion_larga' => 'sometimes|string',
                'imagen_url' => 'sometimes|nullable|string|url',
                'precio_neto' => 'sometimes|numeric|min:0',
                'stock_actual' => 'sometimes|integer|min:0',
                'stock_minimo' => 'sometimes|integer|min:0',
                'stock_bajo' => 'sometimes|integer|min:0',
                'stock_alto' => 'sometimes|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $producto = $this->productoServicio->actualizar((int)$id, $request->all());

            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $producto
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un producto por su ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $eliminado = $this->productoServicio->eliminar((int)$id);

            if (!$eliminado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
