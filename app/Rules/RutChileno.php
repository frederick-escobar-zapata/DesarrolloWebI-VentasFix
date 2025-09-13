<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validación personalizada para RUT chileno
 * 
 * Esta clase valida que un RUT tenga el formato correcto y que el dígito verificador
 * sea válido según el algoritmo oficial chileno. Es súper importante porque evita
 * que se registren RUTs falsos en el sistema.
 * 
 * ¿Qué hace exactamente?
 * - Acepta RUTs con puntos (12.345.678-9) o sin puntos (12345678-9)
 * - Valida que tenga entre 8 y 9 caracteres (sin contar puntos y guión)
 * - Calcula el dígito verificador usando el algoritmo módulo 11
 * - Acepta tanto 'k' como 'K' para el dígito verificador especial
 * 
 * Ejemplos de RUTs válidos:
 * - 12345678-9
 * - 12.345.678-9
 * - 1234567-8
 * - 12345678-k (o K)
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-13
 */
class RutChileno implements ValidationRule
{
    /**
     * Ejecuta la validación del RUT
     * 
     * Este método es llamado automáticamente por Laravel cuando se usa la regla.
     * No te preocupes por llamarlo manualmente, Laravel se encarga de todo.
     * 
     * @param string $attribute El nombre del campo que se está validando
     * @param mixed $value El valor del RUT que necesitamos validar
     * @param Closure $fail Función para reportar error si la validación falla
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Si no hay valor, no validamos (eso lo hace la regla 'required')
        if (empty($value)) {
            return;
        }

        // Limpiamos el RUT: quitamos puntos, espacios y dejamos solo números y guión
        $rut = strtolower(trim($value));
        $rut = preg_replace('/[.\s]/', '', $rut); // Eliminar puntos y espacios
        
        // Verificamos que tenga el formato básico correcto (números-dígito)
        if (!preg_match('/^[\d]+-[0-9kK]$/', $rut)) {
            $fail('El RUT debe tener el formato correcto (ej: 12345678-9).');
            return;
        }

        // Separamos el número del dígito verificador
        $parts = explode('-', $rut);
        $numero = $parts[0];  // La parte numérica del RUT
        $dv = $parts[1];      // El dígito verificador
        
        // El RUT debe tener entre 7 y 8 dígitos (sin contar el verificador)
        if (strlen($numero) < 7 || strlen($numero) > 8) {
            $fail('El RUT debe tener entre 7 y 8 dígitos.');
            return;
        }

        // Ahora viene la magia: calculamos el dígito verificador real
        // Este es el algoritmo oficial chileno usando módulo 11
        $suma = 0;
        $multiplicador = 2;

        // Recorremos los dígitos del RUT de derecha a izquierda
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += (int)$numero[$i] * $multiplicador;
            
            // El multiplicador va de 2 a 7 y luego vuelve a 2
            $multiplicador = $multiplicador == 7 ? 2 : $multiplicador + 1;
        }

        // Calculamos el resto de la división por 11
        $resto = $suma % 11;
        
        // El dígito verificador se calcula según estas reglas:
        $dvCalculado = match($resto) {
            0 => '0',           // Si resto es 0, dígito es 0
            1 => 'k',           // Si resto es 1, dígito es k
            default => (string)(11 - $resto)  // En otros casos, 11 - resto
        };

        // Comparamos el dígito calculado con el que nos dieron
        if ($dv !== $dvCalculado) {
            $fail('El RUT ingresado no es válido. Verifique el dígito verificador.');
        }
    }
}