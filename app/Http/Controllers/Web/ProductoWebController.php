<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ProductoServicio;
use Illuminate\Http\Request;

class ProductoWebController extends Controller
{
    protected ProductoServicio $productoServicio;

    public function __construct(ProductoServicio $productoServicio)
    {
        $this->productoServicio = $productoServicio;
    }

    /**
     * Mostrar listado de productos en la vista web
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $productos = $this->productoServicio->listarTodos();
            
            // Calcular estadísticas para las tarjetas
            $estadisticas = [
                'total' => $productos->count(),
                'activos' => $productos->where('estado', true)->count(),
                'inactivos' => $productos->where('estado', false)->count(),
                'ultimo_mes' => $productos->where('created_at', '>=', now()->subMonth())->count(),
            ];
            
            return view('vertical-menu-template-no-customizer.app-product-list', [
                'productos' => $productos,
                'estadisticas' => $estadisticas,
                'titulo' => 'Lista de Productos',
                'subtitulo' => 'Gestión de productos del sistema VentasFix'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar productos: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear nuevo producto
     */
    public function create()
    {
        return view('vertical-menu-template-no-customizer.app-product-create', [
            'titulo' => 'Crear Producto',
            'subtitulo' => 'Agregar nuevo producto al inventario'
        ]);
    }

    /**
     * Procesar la creación de un nuevo producto
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $request->validate([
                'sku' => 'required|string|max:50|unique:productos,sku',
                'nombre' => 'required|string|max:255',
                'descripcion_corta' => 'required|string|max:500',
                'descripcion_larga' => 'required|string',
                'imagen_url' => 'required|url|max:500',
                'precio_neto' => 'required|numeric|min:0',
                'stock_actual' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'stock_bajo' => 'nullable|integer|min:0',
                'stock_alto' => 'nullable|integer|min:0',
            ]);

            // Crear el producto usando el servicio
            $producto = $this->productoServicio->agregar($request->all());

            return redirect()->route('productos.create')->with('success', 'Producto agregado correctamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear producto: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Mostrar detalles de un producto específico
     */
    public function show($id)
    {
        try {
            $producto = $this->productoServicio->obtenerPorId($id);
            
            if (!$producto) {
                return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
            }
            
            return view('vertical-menu-template-no-customizer.app-product-list-ID', [
                'producto' => $producto,
                'titulo' => 'Detalles del Producto',
                'subtitulo' => 'Información completa del producto'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al cargar producto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        try {
            $producto = $this->productoServicio->obtenerPorId($id);
            
            if (!$producto) {
                return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
            }
            
            return view('vertical-menu-template-no-customizer.app-product-update-id', [
                'producto' => $producto,
                'titulo' => 'Editar Producto',
                'subtitulo' => 'Modificar información del producto'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al cargar producto: ' . $e->getMessage());
        }
    }

    /**
     * Procesar la actualización de un producto
     */
    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $request->validate([
                'sku' => 'required|string|max:50|unique:productos,sku,' . $id,
                'nombre' => 'required|string|max:255',
                'descripcion_corta' => 'required|string|max:500',
                'descripcion_larga' => 'required|string',
                'imagen_url' => 'required|url|max:500',
                'precio_neto' => 'required|numeric|min:0',
                'stock_actual' => 'required|integer|min:0',
                'stock_minimo' => 'required|integer|min:0',
                'stock_bajo' => 'nullable|integer|min:0',
                'stock_alto' => 'nullable|integer|min:0',
            ]);

            // Actualizar el producto usando el servicio
            $producto = $this->productoServicio->actualizar($id, $request->all());

            if (!$producto) {
                return redirect()->route('productos.index')->with('error', 'Producto no encontrado');
            }

            return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar producto: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        try {
            $productoId = (int) $id;
            $resultado = $this->productoServicio->eliminar($productoId);
            
            if ($resultado) {
                return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente');
            } else {
                return redirect()->route('productos.index')->with('error', 'No se pudo eliminar el producto');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al eliminar producto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para buscar productos por ID específico
     */
    public function listById(Request $request)
    {
        try {
            $productos = collect([]);
            $mensaje = '';
            
            if ($request->filled('producto_id')) {
                $productoId = $request->get('producto_id');
                $producto = $this->productoServicio->obtenerPorId($productoId);
                
                if ($producto) {
                    $productos = collect([$producto]);
                    $mensaje = "Producto encontrado con ID: {$productoId}";
                } else {
                    $mensaje = "No se encontró ningún producto con ID: {$productoId}";
                }
            }
            
            return view('vertical-menu-template-no-customizer.app-product-list-ID', [
                'productos' => $productos,
                'mensaje' => $mensaje,
                'titulo' => 'Buscar Producto por ID',
                'subtitulo' => 'Ingresa el ID del producto que deseas buscar'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al buscar producto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para actualizar producto por ID específico
     */
    public function actualizarPorId(Request $request)
    {
        try {
            $productos = collect([]);
            $mensaje = '';
            $producto = null;
            
            if ($request->filled('producto_id') || session('producto_id')) {
                $productoId = $request->get('producto_id') ?? session('producto_id');
                $producto = $this->productoServicio->obtenerPorId($productoId);
                
                if ($producto) {
                    if (session('success')) {
                        $mensaje = session('success');
                    } else {
                        $mensaje = "Producto encontrado. Puedes modificar los datos a continuación:";
                    }
                } else {
                    $mensaje = "No se encontró ningún producto con ID: {$productoId}";
                }
            }
            
            if ($request->isMethod('post') && $request->filled('nombre')) {
                $request->validate([
                    'producto_id' => 'required|integer|exists:productos,id',
                    'nombre' => 'required|string|max:255',
                    'sku' => 'required|string|max:50|unique:productos,sku,' . $request->producto_id,
                    'descripcion_corta' => 'required|string|max:500',
                    'descripcion_larga' => 'required|string',
                    'precio_neto' => 'required|numeric|min:0',
                    'precio_venta' => 'required|numeric|min:0',
                    'stock_actual' => 'required|integer|min:0',
                    'stock_minimo' => 'required|integer|min:0',
                    'imagen_url' => 'required|url|max:500',
                ]);

                $datosActualizacion = [
                    'nombre' => $request->get('nombre'),
                    'sku' => $request->get('sku'),
                    'descripcion_corta' => $request->get('descripcion_corta'),
                    'descripcion_larga' => $request->get('descripcion_larga'),
                    'precio_neto' => $request->get('precio_neto'),
                    'precio_venta' => $request->get('precio_venta'),
                    'stock_actual' => $request->get('stock_actual'),
                    'stock_minimo' => $request->get('stock_minimo'),
                    'imagen_url' => $request->get('imagen_url'),
                ];

                $productoActualizado = $this->productoServicio->actualizar($request->producto_id, $datosActualizacion);
                
                if ($productoActualizado) {
                    return redirect()->route('productos.actualizar-por-id')
                        ->with('success', "Producto '{$productoActualizado->nombre}' actualizado exitosamente")
                        ->with('producto_id', $request->producto_id);
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error al actualizar el producto en la base de datos. Verifique que todos los campos estén correctos.');
                }
            }
            
            return view('vertical-menu-template-no-customizer.app-product-update-id', [
                'producto' => $producto,
                'mensaje' => $mensaje,
                'titulo' => 'Actualizar Producto por ID',
                'subtitulo' => 'Busca por ID y actualiza los datos del producto'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error inesperado al procesar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para eliminar producto por ID específico
     */
    public function eliminarPorId(Request $request)
    {
        try {
            $productos = collect([]);
            $mensaje = '';
            $producto = null;
            $buscar = false;
            
            if ($request->filled('producto_id')) {
                $buscar = true;
                $productoId = $request->get('producto_id');
                $producto = $this->productoServicio->obtenerPorId($productoId);
                
                if ($producto) {
                    $mensaje = "Producto encontrado. Revisa los datos antes de eliminar:";
                } else {
                    $mensaje = "No se encontró ningún producto con ID: {$productoId}";
                }
            }
            
            return view('vertical-menu-template-no-customizer.app-product-delete-ID', [
                'producto' => $producto,
                'mensaje' => $mensaje,
                'buscar' => $buscar,
                'titulo' => 'Eliminar Producto por ID',
                'subtitulo' => 'Busca y elimina un producto específico del sistema'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al cargar producto: ' . $e->getMessage());
        }
    }

    /**
     * Procesar eliminación de producto por ID
     */
    public function procesarEliminarPorId(Request $request)
    {
        try {
            $request->validate([
                'producto_id' => 'required|integer|exists:productos,id'
            ]);

            $productoId = (int) $request->producto_id;
            $resultado = $this->productoServicio->eliminar($productoId);
            
            if ($resultado) {
                return redirect()->route('productos.eliminar-por-id')
                    ->with('success', "Producto con ID {$productoId} eliminado exitosamente");
            } else {
                return redirect()->back()
                    ->with('error', 'Error al eliminar el producto');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar eliminación: ' . $e->getMessage());
        }
    }
}