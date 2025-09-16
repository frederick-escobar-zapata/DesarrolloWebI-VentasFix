<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validación personalizada para evitar números en campos de texto
 * 
 * Esta regla valida que un campo no contenga números, solo letras,
 * espacios y algunos caracteres especiales comunes (acentos, ñ, guiones, puntos).
 * Es útil para campos como nombres, razones sociales, rubros, etc.
 * 
 * ¿Qué hace exactamente?
 * - Permite solo letras (incluye acentos y ñ)
 * - Permite espacios entre palabras
 * - Permite guiones (-) y puntos (.)
 * - Rechaza cualquier número (0-9)
 * - Rechaza símbolos especiales como @, #, $, etc.
 * 
 * Ejemplos válidos:
 * - "Juan Pérez"
 * - "Empresa S.A."
 * - "Tecnología e Innovación"
 * - "María José"
 * 
 * Ejemplos inválidos:
 * - "Juan123" (contiene números)
 * - "Empresa2024" (contiene números)
 * - "Contacto@empresa" (contiene símbolos)
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-15
 */
class SoloTexto implements ValidationRule
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
        if ($value === null || $value === '') {
            return;
        }

        // Convertimos a string por si acaso
        $stringValue = (string) $value;
        
        // Patrón que permite:
        // - Letras (a-z, A-Z)
        // - Acentos y caracteres especiales del español (á, é, í, ó, ú, ñ, ü)
        // - Espacios
        // - Guiones (-)
        // - Puntos (.)
        // - Comas (,)
        // - Apostrofes (')
        $patron = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\.\,\']+$/u';
        
        if (!preg_match($patron, $stringValue)) {
            $fail('Este campo solo puede contener letras, espacios y algunos signos de puntuación.');
        }
        
        // Verificación adicional: si contiene números específicamente
        if (preg_match('/\d/', $stringValue)) {
            $fail('Este campo no puede contener números.');
        }
    }
}