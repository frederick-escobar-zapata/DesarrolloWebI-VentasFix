<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ClienteServicio;
use App\Rules\RutChileno;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controlador para manejar todas las operaciones web de clientes
 * 
 * Este controlador se encarga de todas las páginas web relacionadas con clientes:
 * crear, buscar, actualizar y eliminar. Es como el "cerebro" que conecta las vistas
 * con la lógica de negocio de los clientes.
 * 
 * ¿Qué hace cada método?
 * - mostrarCrear(): Muestra la página para crear un cliente nuevo
 * - crear(): Procesa el formulario de creación y guarda el cliente
 * - mostrarActualizarPorId(): Muestra la página para actualizar clientes
 * - buscarParaActualizar(): Busca un cliente específico para editarlo
 * - actualizar(): Procesa la actualización de datos del cliente
 * - mostrarEliminarPorId(): Muestra la página para eliminar clientes
 * - buscarParaEliminar(): Busca un cliente para confirmar su eliminación
 * - eliminarPorId(): Borra definitivamente el cliente del sistema
 * 
 * @author VentasFix Team
 * @version 1.0
 * @since 2025-09-13
 */
class ClienteWebController extends Controller
{
    /**
     * Servicio que maneja toda la lógica de negocio de clientes
     * 
     * En lugar de escribir toda la lógica aquí, usamos un servicio especializado.
     * Esto hace que el código sea más limpio y reutilizable.
     */
    protected $clienteServicio;

    /**
     * Inyección de dependencias: Laravel nos da automáticamente el servicio
     * 
     * Esto es super útil porque no tenemos que instanciar el servicio manualmente.
     * Laravel se encarga de crearlo y pasárnoslo cuando el controlador se ejecuta.
     */
    public function __construct(ClienteServicio $clienteServicio)
    {
        $this->clienteServicio = $clienteServicio;
    }

    /**
     * Muestra la página para crear un nuevo cliente
     * 
     * Simplemente retorna la vista del formulario de creación.
     * No necesita datos adicionales porque es un formulario vacío.
     * 
     * @return View La vista con el formulario de creación
     */
    public function mostrarCrear(): View
    {
        return view('vertical-menu-template-no-customizer.app-client-create');
    }

    /**
     * Procesa la creación de un nuevo cliente
     * 
     * Este método se ejecuta cuando el usuario envía el formulario de crear cliente.
     * Valida todos los datos, incluyendo el RUT chileno, y si todo está bien,
     * guarda el cliente en la base de datos.
     * 
     * @param Request $request Los datos del formulario enviado
     * @return RedirectResponse Redirección con mensaje de éxito o error
     */
    public function crear(Request $request): RedirectResponse
    {
        // Aquí definimos todas las reglas de validación
        // Nota como usamos nuestra clase RutChileno personalizada para validar el RUT
        $validated = $request->validate([
            'rut_empresa' => ['required', 'string', 'max:12', 'unique:clientes,rut_empresa', new RutChileno],
            'rubro' => 'required|string|max:100',
            'razon_social' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:500',
            'nombre_contacto' => 'required|string|max:255',
            'email_contacto' => 'required|email|max:255',
        ], [
            // Mensajes personalizados que son más amigables para el usuario
            'rut_empresa.required' => 'El RUT de la empresa es obligatorio.',
            'rut_empresa.unique' => 'Este RUT de empresa ya está registrado.',
            'rubro.required' => 'El rubro es obligatorio.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'nombre_contacto.required' => 'El nombre del contacto es obligatorio.',
            'email_contacto.required' => 'El email del contacto es obligatorio.',
            'email_contacto.email' => 'El email debe tener un formato válido.',
        ]);

        // Intentamos crear el cliente, pero si algo sale mal, manejamos el error
        try {
            $cliente = $this->clienteServicio->agregar($validated);
            return redirect()->back()->with('success', 'Cliente creado exitosamente con ID: ' . $cliente->id);
        } catch (\Exception $e) {
            // Si hay error, regresamos al formulario con los datos que había ingresado
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear el cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra la página de actualización de clientes por ID
     * 
     * Esta página tiene dos estados:
     * 1. Si no hay búsqueda, muestra solo el formulario de búsqueda
     * 2. Si hay búsqueda, muestra el cliente encontrado con el formulario de edición
     * 
     * @param Request $request Puede contener el ID del cliente a buscar
     * @return View La vista con el formulario de búsqueda/edición
     */
    public function mostrarActualizarPorId(Request $request): View
    {
        $cliente = null;
        $mensaje = '';
        
        // Si el usuario envió un ID, intentamos buscar el cliente
        if ($request->has('id') && !empty($request->id)) {
            try {
                $clienteId = (int) $request->id;
                $cliente = $this->clienteServicio->obtenerPorId($clienteId);
                
                if (!$cliente) {
                    $mensaje = "No se encontró un cliente con el ID {$clienteId}.";
                }
            } catch (\Exception $e) {
                $mensaje = 'Error al buscar el cliente: ' . $e->getMessage();
            }
        }
        
        return view('vertical-menu-template-no-customizer.app-client-update-id', [
            'cliente' => $cliente,
            'mensaje' => $mensaje
        ]);
    }

    /**
     * Procesa la actualización de datos de un cliente existente
     * 
     * Este método es más complejo porque debe:
     * 1. Validar que todos los datos nuevos sean correctos
     * 2. Verificar que el RUT no lo esté usando otro cliente
     * 3. Mapear los nombres de campos del formulario a los de la base de datos
     * 4. Actualizar los datos y manejar cualquier error
     * 
     * @param Request $request Datos del formulario de actualización
     * @return RedirectResponse Redirección con mensaje de éxito o error
     */
    public function actualizarPorId(Request $request): RedirectResponse
    {
        // Validación completa: verificamos formato, que el cliente exista, etc.
        $validated = $request->validate([
            'id' => 'required|integer|exists:clientes,id',
            'rut' => ['required', 'string', 'max:12', new RutChileno],
            'rubro' => 'required|string|max:100',
            'razon_social' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'nombre_contacto' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], [
            // Mensajes de error personalizados y claros
            'id.required' => 'El ID del cliente es obligatorio.',
            'id.exists' => 'El cliente especificado no existe.',
            'rut.required' => 'El RUT es obligatorio.',
            'rubro.required' => 'El rubro es obligatorio.',
            'razon_social.required' => 'La razón social es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'nombre_contacto.required' => 'El nombre del contacto es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
        ]);

        // Verificación especial: el RUT no debe estar siendo usado por otro cliente
        if ($this->clienteServicio->rutEmpresaExiste($validated['rut'], $validated['id'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['rut' => 'Este RUT ya está siendo usado por otro cliente.']);
        }

        try {
            $clienteId = (int) $validated['id'];
            unset($validated['id']); // Quitamos el ID porque no se actualiza
            
            // Aquí mapeamos los nombres del formulario a los nombres de la base de datos
            // Esto es necesario porque a veces los nombres no coinciden exactamente
            $datosActualizacion = [
                'rut_empresa' => $validated['rut'],           // rut → rut_empresa
                'rubro' => $validated['rubro'],
                'razon_social' => $validated['razon_social'],
                'telefono' => $validated['telefono'],
                'direccion' => $validated['direccion'] ?? '', // Puede ser null
                'nombre_contacto' => $validated['nombre_contacto'],
                'email_contacto' => $validated['email'],      // email → email_contacto
            ];
            
            $cliente = $this->clienteServicio->actualizar($clienteId, $datosActualizacion);
            
            if ($cliente) {
                return redirect()->back()->with('success', 'Cliente actualizado exitosamente.');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error' => 'No se pudo actualizar el cliente.']);
            }
        } catch (\Exception $e) {
            // Si algo sale mal durante la actualización, mostramos el error
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al actualizar el cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Maneja tanto la vista como la búsqueda para eliminar clientes
     * 
     * Este método tiene doble función (algo así como 2 en 1):
     * - GET: Muestra la página con el formulario de búsqueda vacío
     * - POST: Procesa la búsqueda y muestra el cliente encontrado
     * 
     * ¿Por qué así? Para mantener la experiencia fluida del usuario sin
     * necesidad de redirecciones innecesarias.
     * 
     * @param Request $request Puede contener el ID del cliente a buscar
     * @return View|\Illuminate\Http\RedirectResponse Vista de eliminación o redirección con error
     */
    public function eliminarPorId(Request $request)
    {
        // Si es GET, solo mostramos el formulario vacío
        if ($request->isMethod('GET')) {
            return view('vertical-menu-template-no-customizer.app-client-delete-ID');
        }
        
        // Si es POST, procesamos la búsqueda del cliente
        $validated = $request->validate([
            'cliente_id' => 'required|integer',
        ], [
            'cliente_id.required' => 'El ID del cliente es obligatorio.',
            'cliente_id.integer' => 'El ID del cliente debe ser un número entero.',
        ]);

        $clienteId = (int) $validated['cliente_id'];
        $cliente = $this->clienteServicio->obtenerPorId($clienteId);

        // Si no encontramos el cliente, regresamos con error
        if (!$cliente) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cliente_id' => 'No se encontró un cliente con el ID especificado.']);
        }

        // Si lo encontramos, mostramos la página con los datos del cliente
        return view('vertical-menu-template-no-customizer.app-client-delete-ID')
            ->with('cliente', $cliente)
            ->with('success', 'Cliente encontrado. Revise los datos y confirme la eliminación.');
    }

    /**
     * Procesa la eliminación definitiva del cliente
     * 
     * Este método se ejecuta cuando el usuario confirma que sí quiere eliminar
     * el cliente después de haber revisado sus datos. Es irreversible, así que
     * tenemos validaciones extra para asegurarnos de que todo esté correcto.
     * 
     * @param Request $request Debe contener el ID del cliente a eliminar
     * @return RedirectResponse Redirección con mensaje de éxito o error
     */
    public function procesarEliminarPorId(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cliente_id' => 'required|integer|exists:clientes,id',
        ], [
            'cliente_id.required' => 'El ID del cliente es obligatorio.',
            'cliente_id.exists' => 'El cliente especificado no existe.',
        ]);

        try {
            $clienteId = (int) $validated['cliente_id'];
            $eliminado = $this->clienteServicio->eliminar($clienteId);
            
            if ($eliminado) {
                return redirect()->back()->with('success', 'Cliente eliminado exitosamente.');
            } else {
                return redirect()->back()
                    ->withErrors(['error' => 'No se pudo eliminar el cliente.']);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error al eliminar el cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Muestra la lista completa de todos los clientes
     * 
     * Esta es como la "página principal" de clientes donde puedes ver todos
     * los clientes registrados. También calcula estadísticas básicas que 
     * pueden ser útiles para el dashboard.
     * 
     * @return View Vista con la lista completa de clientes y estadísticas
     */
    public function index(): View
    {
        try {
            $clientes = $this->clienteServicio->listarTodos();
            
            // Calculamos algunas estadísticas útiles para mostrar
            $dashboardData = [
                'totalClientes' => $clientes->count(),
                'clientesActivos' => $clientes->where('activo', true)->count(),
                'clientesInactivos' => $clientes->where('activo', false)->count(),
            ];
            
            return view('vertical-menu-template-no-customizer.app-client-list', compact('clientes', 'dashboardData'));
        } catch (\Exception $e) {
            // Si hay error, mostramos datos vacíos pero sin romper la página
            $clientes = collect();
            $dashboardData = [
                'totalClientes' => 0,
                'clientesActivos' => 0,
                'clientesInactivos' => 0,
            ];
            
            return view('vertical-menu-template-no-customizer.app-client-list', compact('clientes', 'dashboardData'))
                ->with('error', 'Error al cargar los clientes: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la página de búsqueda de cliente específico por ID
     * 
     * Similar al método de eliminar, pero este solo busca y muestra el cliente
     * sin opción de eliminarlo. Útil para consultas rápidas.
     * 
     * @param Request $request Puede contener el ID del cliente a buscar
     * @return View Vista de búsqueda con o sin resultado
     */
    public function mostrarListarPorId(Request $request): View
    {
        $clientes = collect(); // Empezamos con una colección vacía
        
        // Si hay una búsqueda, intentamos encontrar el cliente
        if ($request->has('cliente_id') && $request->cliente_id) {
            try {
                $clienteId = (int) $request->cliente_id;
                $cliente = $this->clienteServicio->obtenerPorId($clienteId);
                
                if ($cliente) {
                    $clientes = collect([$cliente]); // Lo convertimos en colección para la vista
                }
            } catch (\Exception $e) {
                return view('vertical-menu-template-no-customizer.app-client-list-ID', compact('clientes'))
                    ->with('error', 'Error al buscar el cliente: ' . $e->getMessage());
            }
        }
        
        return view('vertical-menu-template-no-customizer.app-client-list-ID', compact('clientes'));
    }
}