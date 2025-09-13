<!doctype html>

{{-- 
  Vista para eliminar clientes por ID en VentasFix
  
  Esta página permite buscar un cliente específico por ID y luego
  eliminarlo del sistema con confirmación. Incluye:
  - Búsqueda de cliente por ID
  - Visualización de datos del cliente a eliminar
  - Confirmación modal para eliminar
  
  Características principales:
  - Búsqueda de cliente por ID
  - Tabla de información del cliente
  - Modal de confirmación para eliminar
  - Mensajes de éxito/error
  - Diseño responsivo con Bootstrap
--}}

<html
  lang="es"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}"
  data-template="vertical-menu-template-no-customizer"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    {{-- Título dinámico basado en los datos del controlador --}}
    <title>{{ $titulo ?? 'Eliminar Cliente' }} - VentasFix</title>

    {{-- Descripción meta para SEO --}}
    <meta name="description" content="{{ $subtitulo ?? 'Eliminación de clientes del sistema VentasFix' }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="index.html" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span style="color: var(--bs-primary)">
                  <svg width="30" height="24" viewBox="0 0 250 196" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.3002 1.25469L56.655 28.6432C59.0349 30.1128 60.4839 32.711 60.4839 35.5089V160.63C60.4839 163.468 58.9941 166.097 56.5603 167.553L12.2055 194.107C8.3836 196.395 3.43136 195.15 1.14435 191.327C0.395485 190.075 0 188.643 0 187.184V8.12039C0 3.66447 3.61061 0.0522461 8.06452 0.0522461C9.45056 0.0522461 10.8062 0.409781 12.3002 1.25469Z"
                      fill="currentColor" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z"
                      fill="black" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M0 65.2656L60.4839 99.9629V133.979L0 65.2656Z"
                      fill="black" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M237.71 1.22393L193.355 28.5207C190.97 29.9889 189.516 32.5905 189.516 35.3927V160.631C189.516 163.469 191.006 166.098 193.44 167.555L237.794 194.108C241.616 196.396 246.569 195.151 248.856 191.328C249.605 190.076 250 188.644 250 187.185V8.09597C250 3.64006 246.389 0.027832 241.935 0.027832C240.444 0.027832 239.007 0.360207 237.71 1.22393Z"
                      fill="currentColor" />
                    <path
                      opacity="0.077704"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M250 65.2656L189.516 99.8897V8.43949L250 65.2656Z"
                      fill="black" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.83017 0 11.5477 0.567307 12.2787 1.18923Z"
                      fill="currentColor" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.2787 1.18923L125 70.3075V136.87L0 65.2465V8.06814C0 3.61223 3.61061 0 8.06452 0C9.83017 0 11.5477 0.567307 12.2787 1.18923Z"
                      fill="white"
                      fill-opacity="0.15" />
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M237.721 1.18923L125 70.3075V136.87L250 65.2465V8.06814C250 3.61223 246.389 0 241.935 0C240.17 0 238.452 0.567307 237.721 1.18923Z"
                      fill="currentColor" />
                  </svg>
                </span>
              </span>
              <span class="app-brand-text demo menu-text fw-bold">VentasFix</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item">
              <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboards">Dashboards</div>
              </a>
            </li>

            <!-- Users -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user"></i>
                <div data-i18n="Users">Users</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('usuarios.index') }}" class="menu-link">
                    <div data-i18n="List">Listar Todos</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('usuarios.list-by-id') }}" class="menu-link">
                    <div data-i18n="List by ID">Buscar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('usuarios.create') }}" class="menu-link">
                    <div data-i18n="Add User">Crear Usuario</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('usuarios.actualizar-por-id') }}" class="menu-link">
                    <div data-i18n="Update by ID">Actualizar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('usuarios.eliminar-por-id') }}" class="menu-link">
                    <div data-i18n="Delete by ID">Eliminar por ID</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Products -->
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div data-i18n="Products">Products</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('productos.index') }}" class="menu-link">
                    <div data-i18n="List">Listar Todos</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('productos.list-by-id') }}" class="menu-link">
                    <div data-i18n="List by ID">Buscar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('productos.create') }}" class="menu-link">
                    <div data-i18n="Add Product">Crear Producto</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('productos.actualizar-por-id') }}" class="menu-link">
                    <div data-i18n="Update by ID">Actualizar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('productos.eliminar-por-id') }}" class="menu-link">
                    <div data-i18n="Delete by ID">Eliminar por ID</div>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Clients -->
            <li class="menu-item active open">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-building-store"></i>
                <div data-i18n="Clients">Clients</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="{{ route('clientes.index') }}" class="menu-link">
                    <div data-i18n="List">Listar Todos</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('clientes.list-by-id') }}" class="menu-link">
                    <div data-i18n="List by ID">Buscar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('clientes.create') }}" class="menu-link">
                    <div data-i18n="Add Client">Crear Cliente</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('clientes.actualizar-por-id') }}" class="menu-link">
                    <div data-i18n="Update by ID">Actualizar por ID</div>
                  </a>
                </li>
                <li class="menu-item active">
                  <a href="{{ route('clientes.eliminar-por-id') }}" class="menu-link">
                    <div data-i18n="Delete by ID">Eliminar por ID</div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="ti ti-menu-2 ti-md"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-search-wrapper mb-0">
                  <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                    <i class="ti ti-search ti-md me-2 me-lg-4 ti-lg"></i>
                    <span class="d-none d-md-inline-block text-muted fw-normal ms-4">Buscar (Ctrl+/)</span>
                  </a>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item mt-0" href="pages-account-settings-account.html">
                        <div class="d-flex align-items-center">
                          <div class="flex-shrink-0 me-2">
                            <div class="avatar avatar-online">
                              <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0">John Doe</h6>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="pages-profile-user.html">
                        <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="pages-account-settings-account.html">
                        <i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="pages-account-settings-billing.html">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 ti ti-file-dollar me-3 ti-md"></i><span class="flex-grow-1 align-middle">Billing</span>
                          <span class="flex-shrink-0 badge bg-danger d-flex align-items-center justify-content-center">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="pages-faq.html">
                        <i class="ti ti-question-mark me-3 ti-md"></i><span class="align-middle">FAQ</span>
                      </a>
                    </li>
                    <li>
                      <div class="d-grid px-2 pt-2 pb-1">
                        <a class="btn btn-sm btn-danger d-flex" href="auth-login-cover.html" target="_blank">
                          <small class="align-middle">Logout</small>
                          <i class="ti ti-logout ms-2 ti-14px"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>

            <!-- Search Small Screens -->
            <div class="navbar-search-wrapper search-input-wrapper d-none">
              <input
                type="text"
                class="form-control search-input container-xxl border-0"
                placeholder="Search..."
                aria-label="Search..." />
              <i class="ti ti-x search-toggler cursor-pointer"></i>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              {{-- 
                Formulario de búsqueda por ID - Primera fase del proceso de eliminación
                
                Este formulario permite al usuario ingresar el ID del cliente que desea eliminar.
                Utiliza método POST para enviar la búsqueda al controlador y encontrar el cliente.
                Una vez encontrado, la página se refresca mostrando los datos del cliente.
              --}}
              <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-0">Buscar Cliente por ID</h5>
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('clientes.eliminar-por-id') }}" class="d-flex gap-3 align-items-end">
                    @csrf {{-- Token de seguridad requerido por Laravel para formularios POST --}}
                    <div class="flex-grow-1">
                      <label for="cliente_id" class="form-label">ID del Cliente</label>
                      <input 
                        type="number" 
                        class="form-control @error('cliente_id') is-invalid @enderror" 
                        id="cliente_id" 
                        name="cliente_id" 
                        placeholder="Ingrese el ID del cliente..." 
                        value="{{ request('cliente_id') }}" {{-- Mantiene el valor después de la búsqueda --}}
                        min="1" {{-- Solo acepta números positivos --}}
                        required>
                      @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                    <div>
                      <button type="submit" class="btn btn-primary">
                        <i class="ti ti-search me-2"></i>Buscar por ID
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Mensajes de éxito/error -->
              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="ti ti-check me-2"></i>
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="ti ti-alert-triangle me-2"></i>
                  <strong>Error:</strong> 
                  @foreach($errors->all() as $error)
                    {{ $error }}
                  @endforeach
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif
             
              <!-- Clients List Table -->
              <div class="card">
                <div class="card-header border-bottom">
                  <h5 class="card-title mb-0">{{ $titulo ?? 'Lista de Clientes' }}</h5>
                  <p class="card-text text-muted mt-1">{{ $subtitulo ?? 'Gestión de clientes del sistema VentasFix' }}</p>
                </div>
                <div class="card-datatable table-responsive">
                  <table class="table table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th>ID</th>
                        <th>RUT</th>
                        <th>Razón Social</th>
                        <th>Contacto</th>
                        <th>Email</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($cliente) && $cliente)
                        <tr>
                          <td>{{ $cliente->id }}</td>
                          <td>{{ $cliente->rut_empresa }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                  {{ strtoupper(substr($cliente->razon_social, 0, 1)) }}
                                </span>
                              </div>
                              <div>
                                <h6 class="mb-0">{{ $cliente->razon_social }}</h6>
                                <small class="text-muted">{{ $cliente->rubro }}</small>
                              </div>
                            </div>
                          </td>
                          <td>{{ $cliente->nombre_contacto }}</td>
                          <td>{{ $cliente->email_contacto }}</td>
                          <td>{{ $cliente->created_at->format('d/m/Y H:i') }}</td>
                          <td>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $cliente->id }}">
                              <i class="ti ti-trash me-1"></i>Eliminar
                            </button>
                          </td>
                        </tr>
                      @else
                        <tr>
                          <td colspan="7" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                              <i class="ti ti-building-store-off ti-48px text-muted mb-2"></i>
                              <h6 class="mb-1">No hay clientes</h6>
                              <p class="text-muted">{{ request('cliente_id') ? 'No se encontró el cliente con ID: ' . request('cliente_id') : 'Busque un cliente por ID para eliminarlo' }}</p>
                            </div>
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
            </div>

            @if(isset($cliente) && $cliente)
              {{-- 
                Modal de Confirmación de Eliminación - Segunda fase del proceso
                
                Este modal aparece después de que se encuentra el cliente y el usuario decide eliminarlo.
                Implementa un patrón de confirmación de doble verificación:
                1. Muestra claramente los datos del cliente a eliminar
                2. Advierte que la acción es irreversible
                3. Requiere confirmación explícita del usuario
                
                Es una mejora de UX/UX sobre los alerts básicos de JavaScript,
                proporcionando una experiencia más profesional y segura.
              --}}
              <div class="modal fade" id="deleteModal{{ $cliente->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header bg-danger">
                      <h5 class="modal-title text-white">
                        <i class="ti ti-alert-triangle me-2"></i>Confirmar Eliminación
                      </h5>
                      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="text-center">
                        {{-- Icono grande para impacto visual --}}
                        <i class="ti ti-building-store text-danger mb-3" style="font-size: 4rem;"></i>
                        <h4 class="mb-3">¿Está seguro de que desea eliminar este cliente?</h4>
                        
                        {{-- Resumen claro de los datos del cliente a eliminar --}}
                        <div class="alert alert-warning">
                          <strong>Cliente:</strong> {{ $cliente->razon_social }}<br>
                          <strong>RUT:</strong> {{ $cliente->rut_empresa }}<br>
                          <strong>ID:</strong> {{ $cliente->id }}
                        </div>
                        
                        {{-- Advertencia clara sobre la irreversibilidad --}}
                        <p class="text-danger">
                          <i class="ti ti-alert-triangle me-1"></i>
                          <strong>Esta acción no se puede deshacer</strong>
                        </p>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancelar
                      </button>
                      <form method="POST" action="{{ route('clientes.eliminar-por-id.procesar') }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">
                        <button type="submit" class="btn btn-danger">
                          <i class="ti ti-trash me-1"></i>Sí, Eliminar
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endif
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl">
                <div
                  class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                  <div class="text-body">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , made with ❤️ by <a href="https://pixinvent.com" target="_blank" class="footer-link">Pixinvent</a>
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="https://themeforest.net/licenses/standard" class="footer-link me-4" target="_blank"
                      >License</a
                    >
                    <a href="https://1.envato.market/pixinvent_portfolio" target="_blank" class="footer-link me-4"
                      >More Themes</a
                    >

                    <a
                      href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
                      target="_blank"
                      class="footer-link me-4"
                      >Documentation</a
                    >

                    <a href="https://pixinvent.ticksy.com/" target="_blank" class="footer-link d-none d-sm-inline-block"
                      >Support</a
                    >
                  </div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- VentasFix Custom JS -->
    <script>
        $(document).ready(function() {
            // Configuración para silenciar advertencias de DataTables
            $.fn.dataTable.ext.errMode = 'none';
        });
    </script>
  </body>
</html>