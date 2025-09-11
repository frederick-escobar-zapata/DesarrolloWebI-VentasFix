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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id()->unique();
            $table->string('rut_empresa');
            $table->string('rubro');
            $table->string('razon_social');
            $table->string('telefono');
            $table->text('direccion');
            $table->string('nombre_contacto');
            $table->string('email_contacto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
