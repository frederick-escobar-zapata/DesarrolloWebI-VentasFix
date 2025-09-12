<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UsuarioServicio
{
    /**
     * Listar todos los usuarios
     * 
     * @return Collection
     */
    public function listarTodos(): Collection
    {
        return User::all();
    }

    /**
     * Obtener los datos de un usuario por su ID
     * 
     * @param int $id
     * @return User|null
     */
    public function obtenerPorId(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Agregar un nuevo usuario
     * 
     * @param array $datos
     * @return User
     */
    public function agregar(array $datos): User
    {
        // Encriptar la contraseña
        if (isset($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        }

        // Crear el usuario
        return User::create($datos);
    }

    /**
     * Actualizar un usuario por su ID
     * 
     * @param int $id
     * @param array $datos
     * @return User|null
     */
    public function actualizar(int $id, array $datos): ?User
    {
        $usuario = User::find($id);
        
        if (!$usuario) {
            return null;
        }

        // Encriptar la contraseña si se está actualizando
        if (isset($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        }

        // Actualizar el usuario
        $usuario->update($datos);
        
        return $usuario->fresh(); // Devolver el usuario actualizado
    }

    /**
     * Eliminar un usuario por su ID
     * 
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        $usuario = User::find($id);
        
        if (!$usuario) {
            return false;
        }

        return $usuario->delete();
    }

    /**
     * Verificar si un usuario existe por ID
     * 
     * @param int $id
     * @return bool
     */
    public function existe(int $id): bool
    {
        return User::where('id', $id)->exists();
    }

    /**
     * Verificar si un RUT ya está en uso
     * 
     * @param string $rut
     * @param int|null $excluirId
     * @return bool
     */
    public function rutExiste(string $rut, ?int $excluirId = null): bool
    {
        $query = User::where('rut', $rut);
        
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        
        return $query->exists();
    }

    /**
     * Verificar si un email ya está en uso
     * 
     * @param string $email
     * @param int|null $excluirId
     * @return bool
     */
    public function emailExiste(string $email, ?int $excluirId = null): bool
    {
        $query = User::where('email', $email);
        
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        
        return $query->exists();
    }
}