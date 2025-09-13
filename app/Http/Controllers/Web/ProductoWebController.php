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
            
            return view('vertical-menu-template-no-customizer.app-ecommerce-product-list', [
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
        return view('vertical-menu-template-no-customizer.app-ecommerce-product-add', [
            'titulo' => 'Crear Producto',
            'subtitulo' => 'Agregar nuevo producto al inventario'
        ]);
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
            
            return view('vertical-menu-template-no-customizer.app-ecommerce-product-view', [
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
            
            return view('vertical-menu-template-no-customizer.app-ecommerce-product-edit', [
                'producto' => $producto,
                'titulo' => 'Editar Producto',
                'subtitulo' => 'Modificar información del producto'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al cargar producto: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        try {
            $resultado = $this->productoServicio->eliminar($id);
            
            if ($resultado) {
                return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente');
            } else {
                return redirect()->route('productos.index')->with('error', 'No se pudo eliminar el producto');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('productos.index')->with('error', 'Error al eliminar producto: ' . $e->getMessage());
        }
    }
}