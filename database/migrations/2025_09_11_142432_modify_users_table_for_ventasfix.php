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
        Schema::table('users', function (Blueprint $table) {
            // Eliminar columnas no necesarias
            $table->dropColumn(['name', 'email_verified_at']);
            
            // Agregar nuevas columnas para VentasFix
            $table->string('rut')->unique()->after('id');
            $table->string('nombre')->after('rut');
            $table->string('apellido')->after('nombre');
            
            // Modificar email para que sea único y tenga formato específico
            $table->string('email')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Restaurar columnas originales
            $table->string('name')->after('id');
            $table->timestamp('email_verified_at')->nullable()->after('email');
            
            // Eliminar las columnas agregadas
            $table->dropColumn(['rut', 'nombre', 'apellido']);
        });
    }
};
