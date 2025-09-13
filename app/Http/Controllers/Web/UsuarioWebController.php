<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\UsuarioServicio;
use Illuminate\Http\Request;

/**
 * Controlador Web para la gestión de usuarios
 * 
 * Este controlador maneja todas las operaciones web relacionadas con usuarios:
 * - Lista de usuarios completa
 * - Búsqueda por ID
 * - Creación de nuevos usuarios
 * - Actualización de usuarios existentes
 * - Eliminación de usuarios
 * 
 * Utiliza el patrón de servicio para separar la lógica de negocio
 * de la presentación web.
 */
class UsuarioWebController extends Controller
{
    protected UsuarioServicio $usuarioServicio;

    /**
     * Constructor del controlador
     * 
     * Inyectamos el servicio de usuario para manejar toda la lógica
     * de negocio relacionada con usuarios
     */
    public function __construct(UsuarioServicio $usuarioServicio)
    {
        $this->usuarioServicio = $usuarioServicio;
    }

    /**
     * Mostrar listado de usuarios en la vista web
     * 
     * Esta función carga todos los usuarios y los envía a la vista
     * para mostrar una tabla con la información completa.
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Obtenemos todos los usuarios a través del servicio
            $usuarios = $this->usuarioServicio->listarTodos();
            
            // Retornamos la vista con los datos necesarios
            return view('vertical-menu-template-no-customizer.app-user-list', [
                'usuarios' => $usuarios,
                'titulo' => 'Lista de Usuarios',
                'subtitulo' => 'Gestión de usuarios del sistema VentasFix'
            ]);
            
        } catch (\Exception $e) {
            // Si hay error, regresamos con mensaje de error
            return redirect()->back()->with('error', 'Error al cargar usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de búsqueda por ID
     * 
     * Esta función maneja tanto la visualización del formulario de búsqueda
     * como la búsqueda actual cuando se proporciona un ID.
     */
    public function listById(Request $request)
    {
        try {
            // Inicializamos variables para la vista
            $usuarios = collect([]);
            $mensaje = '';
            
            // Verificamos si el usuario envió un ID para buscar
            if ($request->filled('usuario_id')) {
                $usuarioId = $request->get('usuario_id');
                
                // Buscamos el usuario específico por ID
                $usuario = $this->usuarioServicio->obtenerPorId($usuarioId);
                
                if ($usuario) {
                    // Usuario encontrado - lo convertimos en colección para la vista
                    $usuarios = collect([$usuario]);
                    $mensaje = "Usuario encontrado con ID: {$usuarioId}";
                } else {
                    // No se encontró el usuario con ese ID
                    $mensaje = "No se encontró ningún usuario con ID: {$usuarioId}";
                }
            }
            
            // Enviamos los datos a la vista de búsqueda por ID
            return view('vertical-menu-template-no-customizer.app-user-list-ID', [
                'usuarios' => $usuarios,
                'mensaje' => $mensaje,
                'titulo' => 'Buscar Usuario por ID',
                'subtitulo' => 'Ingresa el ID del usuario para ver sus datos'
            ]);
            
        } catch (\Exception $e) {
            // Si algo sale mal, regresamos con error
            return redirect()->back()->with('error', 'Error al buscar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para crear nuevo usuario
     * 
     * Esta función simplemente muestra el formulario de creación
     * con los títulos apropiados para la vista.
     */
    public function create()
    {
        return view('vertical-menu-template-no-customizer.app-user-create', [
            'titulo' => 'Crear Usuario',
            'subtitulo' => 'Agregar nuevo usuario al sistema'
        ]);
    }

    /**
     * Almacenar nuevo usuario en la base de datos
     * 
     * Esta función procesa el formulario de creación, valida los datos
     * y crea el nuevo usuario. Incluye validaciones específicas para
     * nombres, RUT chileno y correos únicos.
     */
    public function store(Request $request)
    {
        try {
            // Primero limpiamos los espacios en blanco de los campos de texto
            // para evitar datos sucios en la base de datos
            $request->merge([
                'nombre' => trim($request->get('nombre')),
                'apellido' => trim($request->get('apellido')),
                'rut' => $request->get('rut') ? trim($request->get('rut')) : null
            ]);

            // Validamos los datos del formulario con reglas específicas
            $request->validate([
                'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/|not_regex:/^\s+$/|not_regex:/^\s*$/',
                'apellido' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/|not_regex:/^\s+$/|not_regex:/^\s*$/',
                'email' => 'required|email|unique:users,email',
                'rut' => 'nullable|string|max:12|regex:/^[0-9]{7,8}-[0-9kK]{1}$/|unique:users,rut',
                'password' => 'required|string|min:6'
            ], [
                // Mensajes personalizados para una mejor experiencia de usuario
                'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
                'nombre.not_regex' => 'El nombre no puede estar vacío o contener solo espacios.',
                'apellido.regex' => 'El apellido solo puede contener letras y espacios.',
                'apellido.not_regex' => 'El apellido no puede estar vacío o contener solo espacios.',
                'rut.regex' => 'El RUT debe tener formato válido (ej: 12345678-9).',
                'rut.unique' => 'Este RUT ya está registrado en el sistema.'
            ]);

            // Preparamos los datos para crear el usuario
            $datosUsuario = [
                'nombre' => $request->get('nombre'),
                'apellido' => $request->get('apellido'),
                'email' => $request->get('email'),
                'rut' => $request->get('rut'),
                'password' => $request->get('password')
            ];

            // Intentamos crear el usuario usando el servicio
            $usuario = $this->usuarioServicio->agregar($datosUsuario);
            
            if ($usuario) {
                // Usuario creado correctamente - redirigimos con mensaje de éxito
                return redirect()->route('usuarios.create')
                    ->with('success', 'Usuario creado exitosamente');
            } else {
                // Si el servicio no pudo crear el usuario
                return redirect()->back()
                    ->with('error', 'Error al crear el usuario')
                    ->withInput(); // Mantenemos los datos del formulario
            }
            
        } catch (\Exception $e) {
            // Si hay cualquier error, regresamos con mensaje y datos del formulario
            return redirect()->back()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de un usuario específico
     * 
     * Esta función busca un usuario por ID y muestra todos sus detalles
     * en una vista dedicada.
     */
    public function show($id)
    {
        try {
            // Buscamos el usuario por su ID
            $usuario = $this->usuarioServicio->obtenerPorId($id);
            
            if (!$usuario) {
                // Si no existe, regresamos a la lista con error
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
            }
            
            // Enviamos el usuario a la vista de detalles
            return view('vertical-menu-template-no-customizer.app-user-view', [
                'usuario' => $usuario,
                'titulo' => 'Detalles del Usuario',
                'subtitulo' => 'Información completa del usuario'
            ]);
            
        } catch (\Exception $e) {
            // Si hay error al buscar, regresamos a la lista principal
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de edición para un usuario específico
     * 
     * Esta función carga un usuario por ID y muestra el formulario
     * de edición con sus datos actuales.
     */
    public function edit($id)
    {
        try {
            // Buscamos el usuario que se desea editar
            $usuario = $this->usuarioServicio->obtenerPorId($id);
            
            if (!$usuario) {
                // Si no existe, regresamos con error
                return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado');
            }
            
            // Mostramos el formulario de edición con los datos actuales
            return view('vertical-menu-template-no-customizer.app-user-edit', [
                'usuario' => $usuario,
                'titulo' => 'Editar Usuario',
                'subtitulo' => 'Modificar información del usuario'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para actualizar usuario por ID específico
     * 
     * Esta función permite buscar un usuario por ID y luego mostrar
     * un formulario para actualizarlo. Es diferente a edit() porque
     * primero requiere buscar el usuario.
     */
    public function actualizarPorId(Request $request)
    {
        try {
            // Inicializamos las variables para la vista
            $usuarios = collect([]);
            $mensaje = '';
            $usuario = null;
            
            // Verificamos si se envió un ID para buscar
            if ($request->filled('usuario_id')) {
                $usuarioId = $request->get('usuario_id');
                
                // Buscamos el usuario específico por ID
                $usuario = $this->usuarioServicio->obtenerPorId($usuarioId);
                
                if ($usuario) {
                    // Usuario encontrado - mostramos mensaje alentador
                    $mensaje = "Usuario encontrado. Puedes modificar los datos a continuación:";
                } else {
                    // Usuario no encontrado
                    $mensaje = "No se encontró ningún usuario con ID: {$usuarioId}";
                }
            }
            
            // Verificamos si se está enviando una actualización (formulario completo)
            if ($request->isMethod('post') && $request->filled('nombre')) {
                // Validamos todos los campos del formulario
                $request->validate([
                    'usuario_id' => 'required|integer|exists:users,id',
                    'nombre' => 'required|string|max:100',
                    'apellido' => 'required|string|max:100',
                    'email' => 'required|email|unique:users,email,' . $request->usuario_id,
                    'rut' => 'nullable|string|max:20'
                ]);

                $datosActualizacion = [
                    'nombre' => $request->get('nombre'),
                    'apellido' => $request->get('apellido'),
                    'email' => $request->get('email'),
                    'rut' => $request->get('rut')
                ];

                $usuarioActualizado = $this->usuarioServicio->actualizar($request->usuario_id, $datosActualizacion);
                
                if ($usuarioActualizado) {
                    return redirect()->route('usuarios.actualizar-por-id')
                        ->with('success', "Usuario con ID {$request->usuario_id} actualizado exitosamente");
                } else {
                    $mensaje = "Error al actualizar el usuario";
                }
            }
            
            return view('vertical-menu-template-no-customizer.app-user-update-ID', [
                'usuario' => $usuario,
                'mensaje' => $mensaje,
                'titulo' => 'Actualizar Usuario por ID',
                'subtitulo' => 'Busca por ID y actualiza los datos del usuario'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para eliminar usuario por ID específico
     */
    public function eliminarPorId(Request $request)
    {
        try {
            $usuarios = collect([]);
            $mensaje = '';
            $usuario = null;
            
            // Si se proporciona un ID para buscar
            if ($request->filled('usuario_id') && !$request->filled('confirmar_eliminacion')) {
                $usuarioId = $request->get('usuario_id');
                $usuario = $this->usuarioServicio->obtenerPorId($usuarioId);
                
                if ($usuario) {
                    $mensaje = "Usuario encontrado. ¿Estás seguro de que deseas eliminarlo?";
                } else {
                    $mensaje = "No se encontró ningún usuario con ID: {$usuarioId}";
                }
            }
            
            // Verificamos si el usuario quiere proceder con la eliminación
            if ($request->isMethod('post') && $request->filled('confirmar_eliminacion')) {
                $usuarioId = $request->get('usuario_id');
                
                // Intentamos eliminar el usuario usando el servicio
                $eliminado = $this->usuarioServicio->eliminar($usuarioId);
                
                if ($eliminado) {
                    // Eliminación exitosa - regresamos con mensaje de éxito
                    return redirect()->route('usuarios.eliminar-por-id')
                        ->with('success', "Usuario con ID {$usuarioId} eliminado exitosamente");
                } else {
                    // Error en la eliminación
                    $mensaje = "Error al eliminar el usuario. Es posible que no exista.";
                }
            }
            
            // Enviamos los datos a la vista de eliminación por ID
            return view('vertical-menu-template-no-customizer.app-user-delete-ID', [
                'usuario' => $usuario,
                'mensaje' => $mensaje,
                'titulo' => 'Eliminar Usuario por ID',
                'subtitulo' => 'Busca por ID y elimina el usuario del sistema'
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al procesar eliminación: ' . $e->getMessage());
        }
    }

    /**
     * Procesar la eliminación de un usuario específico con confirmación
     * 
     * Este método maneja la eliminación definitiva de un usuario después
     * de que se haya confirmado la acción escribiendo "ELIMINAR".
     */
    public function procesarEliminarPorId(Request $request)
    {
        try {
            // Validamos que se proporcionen los datos necesarios
            $request->validate([
                'usuario_id' => 'required|integer|min:1',
                'confirmacion' => 'required|string|in:ELIMINAR'
            ], [
                // Mensajes personalizados para una mejor experiencia
                'usuario_id.required' => 'El ID del usuario es obligatorio',
                'usuario_id.integer' => 'El ID debe ser un número entero',
                'usuario_id.min' => 'El ID debe ser mayor que 0',
                'confirmacion.required' => 'Debe confirmar escribiendo "ELIMINAR"',
                'confirmacion.in' => 'Debe escribir exactamente "ELIMINAR" para confirmar'
            ]);

            $usuarioId = $request->input('usuario_id');
            
            // Antes de eliminar, verificamos que el usuario realmente existe
            $usuario = $this->usuarioServicio->obtenerPorId($usuarioId);
            
            if (!$usuario) {
                // El usuario no existe - regresamos con error
                return redirect()->route('usuarios.eliminar-por-id')
                    ->with('error', "No se encontró ningún usuario con ID: {$usuarioId}");
            }
            
            // Procedemos con la eliminación del usuario
            $eliminado = $this->usuarioServicio->eliminar($usuarioId);
            
            if ($eliminado) {
                // Eliminación exitosa - mostramos mensaje con detalles del usuario eliminado
                return redirect()->route('usuarios.eliminar-por-id')
                    ->with('success', "Usuario '{$usuario->nombre} {$usuario->apellido}' (ID: {$usuarioId}) ha sido eliminado exitosamente del sistema");
            } else {
                // Error interno durante la eliminación
                return redirect()->route('usuarios.eliminar-por-id')
                    ->with('error', "Error interno al eliminar el usuario. Inténtelo nuevamente.");
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Errores de validación - regresamos con los errores
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Por favor corrija los errores en el formulario.');
                
        } catch (\Exception $e) {
            // Cualquier otro error no previsto
            return redirect()->back()
                ->with('error', 'Error al procesar la eliminación: ' . $e->getMessage())
                ->withInput();
        }
    }
}
