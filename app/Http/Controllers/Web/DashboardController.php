<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UsuarioServicio;
use App\Services\ProductoServicio;
use App\Services\ClienteServicio;
use Illuminate\Http\Request;

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
            // Obtener contadores usando los Services existentes
            $totalUsuarios = $this->usuarioServicio->listarTodos()->count();
            $totalProductos = $this->productoServicio->listarTodos()->count();
            $totalClientes = $this->clienteServicio->listarTodos()->count();

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
