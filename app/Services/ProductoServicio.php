<?php

namespace App\Services;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Collection;

class ProductoServicio
{
    /**
     * Listar todos los productos
     * 
     * @return Collection
     */
    public function listarTodos(): Collection
    {
        return Producto::all();
    }

    /**
     * Obtener los datos de un producto por su ID
     * 
     * @param int $id
     * @return Producto|null
     */
    public function obtenerPorId(int $id): ?Producto
    {
        return Producto::find($id);
    }

    /**
     * Obtener un producto por su SKU
     * 
     * @param string $sku
     * @return Producto|null
     */
    public function obtenerPorSku(string $sku): ?Producto
    {
        return Producto::where('sku', $sku)->first();
    }

    /**
     * Agregar un nuevo producto
     * 
     * @param array $datos
     * @return Producto
     */
    public function agregar(array $datos): Producto
    {
        // Calcular precio de venta si no se proporciona (precio_neto + 19% IVA)
        if (isset($datos['precio_neto']) && !isset($datos['precio_venta'])) {
            $datos['precio_venta'] = $datos['precio_neto'] * 1.19;
        }

        return Producto::create($datos);
    }

    /**
     * Actualizar un producto por su ID
     * 
     * @param int $id
     * @param array $datos
     * @return Producto|null
     */
    public function actualizar(int $id, array $datos): ?Producto
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return null;
        }

        // Recalcular precio de venta si se actualiza el precio neto
        if (isset($datos['precio_neto'])) {
            $datos['precio_venta'] = $datos['precio_neto'] * 1.19;
        }

        $producto->update($datos);
        
        return $producto->fresh();
    }

    /**
     * Eliminar un producto por su ID
     * 
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return false;
        }

        return $producto->delete();
    }

    /**
     * Verificar si un producto existe por ID
     * 
     * @param int $id
     * @return bool
     */
    public function existe(int $id): bool
    {
        return Producto::where('id', $id)->exists();
    }

    /**
     * Verificar si un SKU ya está en uso
     * 
     * @param string $sku
     * @param int|null $excluirId
     * @return bool
     */
    public function skuExiste(string $sku, ?int $excluirId = null): bool
    {
        $query = Producto::where('sku', $sku);
        
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        
        return $query->exists();
    }

    /**
     * Obtener productos con stock bajo
     * 
     * @return Collection
     */
    public function obtenerConStockBajo(): Collection
    {
        return Producto::whereColumn('stock_actual', '<=', 'stock_bajo')->get();
    }

    /**
     * Obtener productos sin stock
     * 
     * @return Collection
     */
    public function obtenerSinStock(): Collection
    {
        return Producto::where('stock_actual', 0)->get();
    }

    /**
     * Actualizar stock de un producto
     * 
     * @param int $id
     * @param int $nuevoStock
     * @return Producto|null
     */
    public function actualizarStock(int $id, int $nuevoStock): ?Producto
    {
        $producto = Producto::find($id);
        
        if (!$producto) {
            return null;
        }

        $producto->update(['stock_actual' => $nuevoStock]);
        
        return $producto->fresh();
    }
}