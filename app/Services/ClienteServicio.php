<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Collection;

class ClienteServicio
{
    /**
     * Listar todos los clientes
     * 
     * @return Collection
     */
    public function listarTodos(): Collection
    {
        return Cliente::all();
    }

    /**
     * Obtener los datos de un cliente por su ID
     * 
     * @param int $id
     * @return Cliente|null
     */
    public function obtenerPorId(int $id): ?Cliente
    {
        return Cliente::find($id);
    }

    /**
     * Obtener un cliente por su RUT de empresa
     * 
     * @param string $rutEmpresa
     * @return Cliente|null
     */
    public function obtenerPorRutEmpresa(string $rutEmpresa): ?Cliente
    {
        return Cliente::where('rut_empresa', $rutEmpresa)->first();
    }

    /**
     * Agregar un nuevo cliente
     * 
     * @param array $datos
     * @return Cliente
     */
    public function agregar(array $datos): Cliente
    {
        return Cliente::create($datos);
    }

    /**
     * Actualizar un cliente por su ID
     * 
     * @param int $id
     * @param array $datos
     * @return Cliente|null
     */
    public function actualizar(int $id, array $datos): ?Cliente
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return null;
        }

        $cliente->update($datos);
        
        return $cliente->fresh();
    }

    /**
     * Eliminar un cliente por su ID
     * 
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return false;
        }

        return $cliente->delete();
    }

    /**
     * Verificar si un cliente existe por ID
     * 
     * @param int $id
     * @return bool
     */
    public function existe(int $id): bool
    {
        return Cliente::where('id', $id)->exists();
    }

    /**
     * Verificar si un RUT de empresa ya está en uso
     * 
     * @param string $rutEmpresa
     * @param int|null $excluirId
     * @return bool
     */
    public function rutEmpresaExiste(string $rutEmpresa, ?int $excluirId = null): bool
    {
        $query = Cliente::where('rut_empresa', $rutEmpresa);
        
        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }
        
        return $query->exists();
    }

    /**
     * Buscar clientes por rubro
     * 
     * @param string $rubro
     * @return Collection
     */
    public function buscarPorRubro(string $rubro): Collection
    {
        return Cliente::where('rubro', 'LIKE', '%' . $rubro . '%')->get();
    }

    /**
     * Buscar clientes por razón social
     * 
     * @param string $razonSocial
     * @return Collection
     */
    public function buscarPorRazonSocial(string $razonSocial): Collection
    {
        return Cliente::where('razon_social', 'LIKE', '%' . $razonSocial . '%')->get();
    }

    /**
     * Obtener clientes ordenados por razón social
     * 
     * @return Collection
     */
    public function listarOrdenadosPorRazonSocial(): Collection
    {
        return Cliente::orderBy('razon_social', 'asc')->get();
    }

    /**
     * Contar total de clientes
     * 
     * @return int
     */
    public function contarTotal(): int
    {
        return Cliente::count();
    }

    /**
     * Obtener clientes por rubro específico
     * 
     * @param string $rubro
     * @return Collection
     */
    public function obtenerPorRubro(string $rubro): Collection
    {
        return Cliente::where('rubro', $rubro)->get();
    }
}