<?php

require __DIR__ . '/bootstrap/app.php';

use App\Http\Controllers\Web\ProductoWebController;
use App\Services\ProductoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

// Crear una aplicación de prueba
$app = \Illuminate\Foundation\Application::getInstance();
$app->boot();

// Obtener el producto antes de la actualización
$productoServicio = new ProductoServicio();
$productoAntes = $productoServicio->obtenerPorId(8);

if ($productoAntes) {
    echo "=== PRODUCTO ANTES DE LA ACTUALIZACIÓN ===" . PHP_EOL;
    echo "ID: " . $productoAntes->id . PHP_EOL;
    echo "Nombre: " . $productoAntes->nombre . PHP_EOL;
    echo "SKU: " . $productoAntes->sku . PHP_EOL;
    echo "Precio Neto: " . $productoAntes->precio_neto . PHP_EOL;
    echo "Stock Actual: " . $productoAntes->stock_actual . PHP_EOL;
    echo PHP_EOL;

    // Datos de actualización
    $datosActualizacion = [
        'nombre' => 'Producto Actualizado mediante Test',
        'sku' => 'TEST-001',
        'descripcion_corta' => 'Descripción actualizada mediante prueba',
        'descripcion_larga' => 'Descripción larga actualizada mediante prueba',
        'precio_neto' => 20000,
        'precio_venta' => 23800, // Se calculará automáticamente
        'stock_actual' => 100,
        'stock_minimo' => 20,
        'imagen_url' => ''
    ];

    // Actualizar el producto
    $productoActualizado = $productoServicio->actualizar(8, $datosActualizacion);

    if ($productoActualizado) {
        echo "=== PRODUCTO DESPUÉS DE LA ACTUALIZACIÓN ===" . PHP_EOL;
        echo "ID: " . $productoActualizado->id . PHP_EOL;
        echo "Nombre: " . $productoActualizado->nombre . PHP_EOL;
        echo "SKU: " . $productoActualizado->sku . PHP_EOL;
        echo "Precio Neto: " . $productoActualizado->precio_neto . PHP_EOL;
        echo "Precio Venta (calculado): " . $productoActualizado->precio_venta . PHP_EOL;
        echo "Stock Actual: " . $productoActualizado->stock_actual . PHP_EOL;
        echo "Stock Mínimo: " . $productoActualizado->stock_minimo . PHP_EOL;
        echo PHP_EOL;
        echo "✅ ACTUALIZACIÓN EXITOSA" . PHP_EOL;
    } else {
        echo "❌ ERROR: No se pudo actualizar el producto" . PHP_EOL;
    }
} else {
    echo "❌ ERROR: No se encontró el producto con ID 8" . PHP_EOL;
}