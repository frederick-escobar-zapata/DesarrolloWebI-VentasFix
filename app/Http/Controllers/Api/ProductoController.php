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
            
            // Verificar si no existen registros
            if (empty($productos) || count($productos) === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'No existen registros de productos',
                    'data' => []
                ], 200);
            }
            
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
            // Validaciones completas con mensajes en español
            $validator = Validator::make($request->all(), [
                'sku' => ['required', 'string', 'unique:productos,sku', 'max:50', 'not_regex:/^\s*$/'],
                'nombre' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
                'descripcion_corta' => ['required', 'string', 'not_regex:/^\s*$/'],
                'descripcion_larga' => ['required', 'string', 'not_regex:/^\s*$/'],
                'imagen_url' => ['required', 'string', 'url', 'not_regex:/^\s*$/'],
                'precio_neto' => ['required', 'numeric', 'min:0', 'regex:/^[0-9]+(\.[0-9]+)?$/'],
                'stock_actual' => ['required', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_minimo' => ['required', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_bajo' => ['required', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_alto' => ['required', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
            ], [
                // Mensajes para SKU
                'sku.required' => 'El SKU es obligatorio.',
                'sku.string' => 'El SKU debe ser una cadena de texto.',
                'sku.unique' => 'Este SKU ya está registrado.',
                'sku.max' => 'El SKU no puede tener más de 50 caracteres.',
                'sku.not_regex' => 'El SKU no puede estar vacío o contener solo espacios.',
                
                // Mensajes para nombre
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'nombre.not_regex' => 'El nombre no puede estar vacío o contener solo espacios.',
                
                // Mensajes para descripción corta
                'descripcion_corta.required' => 'La descripción corta es obligatoria.',
                'descripcion_corta.string' => 'La descripción corta debe ser una cadena de texto.',
                'descripcion_corta.not_regex' => 'La descripción corta no puede estar vacía o contener solo espacios.',
                
                // Mensajes para descripción larga
                'descripcion_larga.required' => 'La descripción larga es obligatoria.',
                'descripcion_larga.string' => 'La descripción larga debe ser una cadena de texto.',
                'descripcion_larga.not_regex' => 'La descripción larga no puede estar vacía o contener solo espacios.',
                
                // Mensajes para imagen URL
                'imagen_url.required' => 'La URL de imagen es obligatoria.',
                'imagen_url.string' => 'La URL de imagen debe ser una cadena de texto.',
                'imagen_url.url' => 'La URL de imagen debe tener un formato válido.',
                'imagen_url.not_regex' => 'La URL de imagen no puede estar vacía o contener solo espacios.',
                
                // Mensajes para precio_neto
                'precio_neto.required' => 'El precio neto es obligatorio.',
                'precio_neto.numeric' => 'El precio neto debe ser un número.',
                'precio_neto.min' => 'El precio neto debe ser mayor o igual a 0.',
                'precio_neto.regex' => 'El precio neto solo puede contener números y punto decimal.',
                
                // Mensajes para stock_actual
                'stock_actual.required' => 'El stock actual es obligatorio.',
                'stock_actual.integer' => 'El stock actual debe ser un número entero.',
                'stock_actual.min' => 'El stock actual debe ser mayor o igual a 0.',
                'stock_actual.regex' => 'El stock actual solo puede contener números.',
                
                // Mensajes para stock_minimo
                'stock_minimo.required' => 'El stock mínimo es obligatorio.',
                'stock_minimo.integer' => 'El stock mínimo debe ser un número entero.',
                'stock_minimo.min' => 'El stock mínimo debe ser mayor o igual a 0.',
                'stock_minimo.regex' => 'El stock mínimo solo puede contener números.',
                
                // Mensajes para stock_bajo
                'stock_bajo.required' => 'El stock bajo es obligatorio.',
                'stock_bajo.integer' => 'El stock bajo debe ser un número entero.',
                'stock_bajo.min' => 'El stock bajo debe ser mayor o igual a 0.',
                'stock_bajo.regex' => 'El stock bajo solo puede contener números.',
                
                // Mensajes para stock_alto
                'stock_alto.required' => 'El stock alto es obligatorio.',
                'stock_alto.integer' => 'El stock alto debe ser un número entero.',
                'stock_alto.min' => 'El stock alto debe ser mayor o igual a 0.',
                'stock_alto.regex' => 'El stock alto solo puede contener números.',
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
            // Validaciones completas con mensajes en español (usando 'sometimes' para actualizaciones parciales)
            $validator = Validator::make($request->all(), [
                'sku' => ['sometimes', 'string', 'unique:productos,sku,' . $id, 'max:50', 'not_regex:/^\s*$/'],
                'nombre' => ['sometimes', 'string', 'max:255', 'not_regex:/^\s*$/'],
                'descripcion_corta' => ['sometimes', 'string', 'not_regex:/^\s*$/'],
                'descripcion_larga' => ['sometimes', 'string', 'not_regex:/^\s*$/'],
                'imagen_url' => ['sometimes', 'required', 'string', 'url', 'not_regex:/^\s*$/'],
                'precio_neto' => ['sometimes', 'numeric', 'min:0', 'regex:/^[0-9]+(\.[0-9]+)?$/'],
                'stock_actual' => ['sometimes', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_minimo' => ['sometimes', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_bajo' => ['sometimes', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
                'stock_alto' => ['sometimes', 'integer', 'min:0', 'regex:/^[0-9]+$/'],
            ], [
                // Mensajes para SKU
                'sku.string' => 'El SKU debe ser una cadena de texto.',
                'sku.unique' => 'Este SKU ya está registrado.',
                'sku.max' => 'El SKU no puede tener más de 50 caracteres.',
                'sku.not_regex' => 'El SKU no puede estar vacío o contener solo espacios.',
                
                // Mensajes para nombre
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
                'nombre.not_regex' => 'El nombre no puede estar vacío o contener solo espacios.',
                
                // Mensajes para descripción corta
                'descripcion_corta.string' => 'La descripción corta debe ser una cadena de texto.',
                'descripcion_corta.not_regex' => 'La descripción corta no puede estar vacía o contener solo espacios.',
                
                // Mensajes para descripción larga
                'descripcion_larga.string' => 'La descripción larga debe ser una cadena de texto.',
                'descripcion_larga.not_regex' => 'La descripción larga no puede estar vacía o contener solo espacios.',
                
                // Mensajes para imagen URL
                'imagen_url.required' => 'La URL de imagen es obligatoria.',
                'imagen_url.string' => 'La URL de imagen debe ser una cadena de texto.',
                'imagen_url.url' => 'La URL de imagen debe tener un formato válido.',
                'imagen_url.not_regex' => 'La URL de imagen no puede estar vacía o contener solo espacios.',
                
                // Mensajes para precio_neto
                'precio_neto.numeric' => 'El precio neto debe ser un número.',
                'precio_neto.min' => 'El precio neto debe ser mayor o igual a 0.',
                'precio_neto.regex' => 'El precio neto solo puede contener números y punto decimal.',
                
                // Mensajes para stock_actual
                'stock_actual.integer' => 'El stock actual debe ser un número entero.',
                'stock_actual.min' => 'El stock actual debe ser mayor o igual a 0.',
                'stock_actual.regex' => 'El stock actual solo puede contener números.',
                
                // Mensajes para stock_minimo
                'stock_minimo.integer' => 'El stock mínimo debe ser un número entero.',
                'stock_minimo.min' => 'El stock mínimo debe ser mayor o igual a 0.',
                'stock_minimo.regex' => 'El stock mínimo solo puede contener números.',
                
                // Mensajes para stock_bajo
                'stock_bajo.integer' => 'El stock bajo debe ser un número entero.',
                'stock_bajo.min' => 'El stock bajo debe ser mayor o igual a 0.',
                'stock_bajo.regex' => 'El stock bajo solo puede contener números.',
                
                // Mensajes para stock_alto
                'stock_alto.integer' => 'El stock alto debe ser un número entero.',
                'stock_alto.min' => 'El stock alto debe ser mayor o igual a 0.',
                'stock_alto.regex' => 'El stock alto solo puede contener números.',
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
