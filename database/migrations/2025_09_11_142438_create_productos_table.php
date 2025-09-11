<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('sku');
            $table->string('nombre');
            $table->text('descripcion_corta');
            $table->text('descripcion_larga');
            $table->string('imagen_url')->nullable();
            $table->decimal('precio_neto', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_bajo')->default(0);
            $table->integer('stock_alto')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
