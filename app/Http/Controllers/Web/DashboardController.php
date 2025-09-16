<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UsuarioServicio;
use App\Services\ProductoServicio;
use App\Services\ClienteServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $usuarioServicio;
    protected $productoServicio;
    protected $clienteServicio;

    public function __construct(
        UsuarioServicio $usuarioServicio,
        ProductoServicio $productoServicio,
        ClienteServicio $clienteServicio
    ) {
        $this->usuarioServicio = $usuarioServicio;
        $this->productoServicio = $productoServicio;
        $this->clienteServicio = $clienteServicio;
    }

    
    public function index()
    {
        try {
            // Obtener contadores usando los Services existentes con manejo de errores
            $totalUsuarios = 0;
            $totalProductos = 0; 
            $totalClientes = 0;
            
            try {
                $totalUsuarios = $this->usuarioServicio->listarTodos()->count();
            } catch (\Exception $e) {
                Log::warning('Error al obtener total usuarios: ' . $e->getMessage());
            }
            
            try {
                $totalProductos = $this->productoServicio->listarTodos()->count();
            } catch (\Exception $e) {
                Log::warning('Error al obtener total productos: ' . $e->getMessage());
            }
            
            try {
                $totalClientes = $this->clienteServicio->listarTodos()->count();
            } catch (\Exception $e) {
                Log::warning('Error al obtener total clientes: ' . $e->getMessage());
            }

            // Datos para el dashboard
            $dashboardData = [
                'totalUsuarios' => $totalUsuarios,
                'totalProductos' => $totalProductos,
                'totalClientes' => $totalClientes,
                'titulo' => 'Dashboard VentasFix',
                'subtitulo' => 'Panel de Administración'
            ];

            return view('vertical-menu-template-no-customizer.index', compact('dashboardData'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar el dashboard: ' . $e->getMessage());
        }
    }
}
