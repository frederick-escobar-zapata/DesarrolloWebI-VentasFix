<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validación personalizada para evitar campos que contengan solo espacios
 * 
 * Esta regla valida que un campo no esté compuesto únicamente por espacios
 * en blanco. Es importante para asegurar que los campos tengan contenido real
 * y no solo caracteres de espaciado.
 * 
 * ¿Qué hace exactamente?
 * - Verifica que después de hacer trim() el campo no quede vacío
 * - Evita que se guarden campos como "   " (solo espacios)
 * - Permite contenido válido con espacios entre palabras
 * 
 * Ejemplos:
 * - "   " → INVÁLIDO (solo espacios)
 * - "" → Se maneja con la regla 'required' 
 * - "Juan Pérez" → VÁLIDO (contenido real con espacios)
 * - "  Empresa  " → VÁLIDO (contenido real, espacios al inicio/final)
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-15
 */
class NoSoloEspacios implements ValidationRule
{
    /**
     * Ejecuta la validación del campo
     * 
     * @param string $attribute El nombre del campo que se está validando
     * @param mixed $value El valor que necesitamos validar
     * @param Closure $fail Función para reportar error si la validación falla
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Si no hay valor, no validamos (eso lo hace la regla 'required')
        if ($value === null) {
            return;
        }

        // Convertimos a string por si acaso
        $stringValue = (string) $value;
        
        // Si después de quitar espacios al inicio y final queda vacío,
        // significa que el campo solo contenía espacios
        if (trim($stringValue) === '') {
            $fail('El campo no puede contener solo espacios en blanco.');
        }
    }
}