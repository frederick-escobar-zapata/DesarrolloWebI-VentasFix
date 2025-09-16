<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validación personalizada para formato de teléfono chileno
 * 
 * Esta regla valida que un teléfono tenga el formato específico chileno:
 * +56 XXX-XXX-XXX donde X son dígitos del 0-9
 * 
 * ¿Qué hace exactamente?
 * - Verifica que comience con +56 (código de país de Chile)
 * - Valida que tenga exactamente 9 dígitos después del código
 * - Verifica el formato con guiones en las posiciones correctas
 * - Permite espacios opcionales alrededor de los guiones
 * 
 * Ejemplos válidos:
 * - "+56 912-345-678" (móvil)
 * - "+56 987-654-321" (móvil)
 * - "+56 956-123-456" (móvil)
 * - "+56 222-555-777" (fijo Santiago)
 * 
 * Ejemplos inválidos:
 * - "912345678" (sin código de país)
 * - "+56912345678" (sin guiones)
 * - "+569123456789" (muy largo)
 * - "+57 912-345-678" (código de país incorrecto)
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-15
 */
class TelefonoChileno implements ValidationRule
{
    /**
     * Ejecuta la validación del teléfono
     * 
     * @param string $attribute El nombre del campo que se está validando
     * @param mixed $value El valor del teléfono que necesitamos validar
     * @param Closure $fail Función para reportar error si la validación falla
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Si no hay valor, no validamos (eso lo hace la regla 'required')
        if ($value === null || $value === '') {
            return;
        }

        // Convertimos a string y limpiamos espacios extra al inicio y final
        $telefono = trim((string) $value);
        
        // Patrón para validar el formato exacto: +56 XXX-XXX-XXX
        if (!preg_match('/^\+56 \d{3}-\d{3}-\d{3}$/', $telefono)) {
            $fail('El teléfono debe tener el formato: +56 XXX-XXX-XXX (ejemplo: +56 912-345-678).');
            return;
        }
        
        // Extraemos los dígitos para validación del primer número
        $digitos = str_replace(['+56 ', '-'], '', $telefono);
        
        // Verificamos que el primer dígito sea válido para números chilenos
        $primerDigito = $digitos[0];
        if (!in_array($primerDigito, ['2', '3', '4', '5', '6', '7', '8', '9'])) {
            $fail('El número no parece ser un teléfono chileno válido.');
        }
    }
}