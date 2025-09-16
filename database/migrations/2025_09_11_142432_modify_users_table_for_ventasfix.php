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
            // Verificar y eliminar columnas solo si existen
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            
            // Agregar nuevas columnas para VentasFix solo si no existen
            if (!Schema::hasColumn('users', 'rut')) {
                $table->string('rut')->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'nombre')) {
                $table->string('nombre')->after('rut');
            }
            if (!Schema::hasColumn('users', 'apellido')) {
                $table->string('apellido')->after('nombre');
            }
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
