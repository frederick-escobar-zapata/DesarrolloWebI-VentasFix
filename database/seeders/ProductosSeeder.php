<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'sku' => 'LAP001',
                'nombre' => 'Laptop Dell Inspiron 15',
                'descripcion_corta' => 'Laptop Dell Inspiron 15 pulgadas',
                'descripcion_larga' => 'Laptop Dell Inspiron 15 pulgadas, procesador Intel i5, 8GB RAM, 256GB SSD',
                'imagen_url' => 'https://example.com/images/laptop-dell.jpg',
                'precio_neto' => 500000.00,
                'precio_venta' => 595000.00, // precio_neto + 19% IVA
                'stock_actual' => 25,
                'stock_minimo' => 5,
                'stock_bajo' => 10,
                'stock_alto' => 50,
            ],
            [
                'sku' => 'MON002',
                'nombre' => 'Monitor Samsung 24"',
                'descripcion_corta' => 'Monitor Full HD 24 pulgadas',
                'descripcion_larga' => 'Monitor Samsung Full HD 24 pulgadas, resolución 1920x1080, conexión HDMI y VGA',
                'imagen_url' => 'https://example.com/images/monitor-samsung.jpg',
                'precio_neto' => 150000.00,
                'precio_venta' => 178500.00,
                'stock_actual' => 15,
                'stock_minimo' => 3,
                'stock_bajo' => 5,
                'stock_alto' => 30,
            ],
            [
                'sku' => 'TEC003',
                'nombre' => 'Teclado Mecánico RGB',
                'descripcion_corta' => 'Teclado mecánico con iluminación RGB',
                'descripcion_larga' => 'Teclado mecánico gaming con switches Cherry MX, iluminación RGB personalizable',
                'imagen_url' => 'https://example.com/images/teclado-mecanico.jpg',
                'precio_neto' => 80000.00,
                'precio_venta' => 95200.00,
                'stock_actual' => 40,
                'stock_minimo' => 10,
                'stock_bajo' => 15,
                'stock_alto' => 60,
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->insert(array_merge($producto, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
