<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     */
    protected $table = 'productos';

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'sku',
        'nombre',
        'descripcion_corta',
        'descripcion_larga',
        'imagen_url',
        'precio_neto',
        'precio_venta',
        'stock_actual',
        'stock_minimo',
        'stock_bajo',
        'stock_alto',
    ];

    /**
     * Los atributos que deben ser casteados.
     */
    protected $casts = [
        'precio_neto' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'stock_bajo' => 'integer',
        'stock_alto' => 'integer',
    ];
}
