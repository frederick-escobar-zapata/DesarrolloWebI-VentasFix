<!doctype html>

{{-- 
  Vista para crear nuevos clientes en VentasFix
  
  Esta página permite a los administradores agregar nuevos clientes al sistema.
  Incluye validación en tiempo real, manejo de datos empresariales,
  y gestión de contactos.
  
  Características principales:
  - Formulario con validación JavaScript
  - Validación de RUT empresarial
  - Gestión de datos de contacto
  - Control de rubros empresariales
  - Mensajes de éxito/error con componentes flash
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

    {{-- Título dinámico de la página --}}
    <title>{{ $titulo ?? 'Crear Cliente' }} - VentasFix</title>

    {{-- Meta descripción para SEO --}}
    <meta name="description" content="{{ $subtitulo ?? 'Agregar nuevo cliente al sistema VentasFix' }}" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
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

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                    fill="#7367F0" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                    fill="#161616" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                    fill="#161616" />
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                    fill="#7367F0" />
                </svg>
              </span>
              <span class="app-brand-text demo menu-text fw-bold">VentasFix</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
              <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
              <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
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

            <!-- Apps & Pages -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Apps &amp; Pages</span></li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-mail"></i>
                <div data-i18n="Email">Email</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="app-email.html" class="menu-link">
                    <div data-i18n="Inbox">Inbox</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-users"></i>
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
                <li class="menu-item active">
                  <a href="{{ route('clientes.create') }}" class="menu-link">
                    <div data-i18n="Add Client">Crear Cliente</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="{{ route('clientes.actualizar-por-id') }}" class="menu-link">
                    <div data-i18n="Update by ID">Actualizar por ID</div>
                  </a>
                </li>
                <li class="menu-item">
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
                    <span class="d-none d-md-inline-block text-muted fw-normal ms-4">Buscar clientes...</span>
                  </a>
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Language -->
                <li class="nav-item dropdown-language dropdown">
                  <a
                    class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="ti ti-language rounded-pill ti-md"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);" data-language="en" data-text-direction="ltr">
                        <span>English</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item active" href="javascript:void(0);" data-language="es" data-text-direction="ltr">
                        <span>Español</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ Language -->

                <!-- Style Switcher -->
                <li class="nav-item dropdown-style-switcher dropdown">
                  <a
                    class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <i class="ti ti-md"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                        <span class="align-middle"><i class="ti ti-sun ti-md me-3"></i>Light</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                        <span class="align-middle"><i class="ti ti-moon-stars ti-md me-3"></i>Dark</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                        <span class="align-middle"><i class="ti ti-device-desktop-analytics ti-md me-3"></i>System</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!-- / Style Switcher-->

                <!-- Quick links  -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
                  <a
                    class="nav-link btn btn-text-secondary btn-icon rounded-pill btn-icon dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false">
                    <i class="ti ti-layout-grid-add ti-md"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="dropdown-menu-header border-bottom">
                      <div class="dropdown-header d-flex align-items-center py-3">
                        <h6 class="mb-0 me-auto">Accesos Rápidos</h6>
                        <a
                          href="javascript:void(0)"
                          class="btn btn-text-secondary rounded-pill btn-icon dropdown-shortcuts-add"
                          data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          title="Add shortcuts"
                          ><i class="ti ti-plus text-heading"></i
                        ></a>
                      </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container">
                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="ti ti-users fs-4"></i>
                          </span>
                          <a href="{{ route('clientes.create') }}" class="stretched-link">Crear Cliente</a>
                          <small>Agregar nuevo cliente</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                            <i class="ti ti-package fs-4"></i>
                          </span>
                          <a href="{{ route('productos.create') }}" class="stretched-link">Crear Producto</a>
                          <small>Agregar producto</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Quick links -->

                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-2">
                  <a
                    class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false">
                    <span class="position-relative">
                      <i class="ti ti-bell ti-md"></i>
                      <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                    </span>
                  </a>
                </li>
                <!--/ Notification -->

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
                            <h6 class="mb-0">Administrator</h6>
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
                        <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">Mi Perfil</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="pages-account-settings-account.html">
                        <i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Configuración</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

              {{-- Mensajes de éxito/error --}}
              @if (session('success'))
                <div class="row mb-6">
                  <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <i class="ti ti-check me-2"></i>
                      <strong>¡Éxito!</strong> {{ session('success') }}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  </div>
                </div>
              @endif

              @if ($errors->any())
                <div class="row mb-6">
                  <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <i class="ti ti-alert-triangle me-2"></i>
                      <strong>¡Error!</strong> Por favor, corrija los siguientes errores:
                      <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  </div>
                </div>
              @endif

              {{-- Formulario de creación de clientes --}}
              <div class="row">
                <div class="col-12">
                  <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="card">
                      <div class="card-header">
                        <h5 class="card-title mb-0">
                          <i class="ti ti-forms me-2"></i>
                          Crear Cliente
                        </h5>
                        <p class="card-text text-muted">Complete todos los campos requeridos para crear el cliente</p>
                      </div>
                      <div class="card-body">
                        <div class="row g-6">
                          {{-- RUT Empresa --}}
                          <div class="col-md-6">
                            <label for="rut_empresa" class="form-label">
                              RUT de la Empresa <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-id"></i></span>
                              <input
                                type="text"
                                class="form-control @error('rut_empresa') is-invalid @enderror"
                                id="rut_empresa"
                                name="rut_empresa"
                                value="{{ old('rut_empresa') }}"
                                placeholder="12345678-9"
                                pattern="[0-9]{7,8}-[0-9Kk]{1}"
                                title="Formato válido: 12345678-9"
                                maxlength="12"
                                required>
                            </div>
                            @error('rut_empresa')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Razón Social --}}
                          <div class="col-md-6">
                            <label for="razon_social" class="form-label">
                              Razón Social <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-building"></i></span>
                              <input
                                type="text"
                                class="form-control @error('razon_social') is-invalid @enderror"
                                id="razon_social"
                                name="razon_social"
                                value="{{ old('razon_social') }}"
                                placeholder="Empresa S.A."
                                pattern=".*\S+.*"
                                title="No puede contener solo espacios en blanco"
                                maxlength="255"
                                required>
                            </div>
                            @error('razon_social')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Rubro --}}
                          <div class="col-md-6">
                            <label for="rubro" class="form-label">
                              Rubro <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-briefcase"></i></span>
                              <input
                                type="text"
                                class="form-control @error('rubro') is-invalid @enderror"
                                id="rubro"
                                name="rubro"
                                value="{{ old('rubro') }}"
                                placeholder="Tecnología, Comercio, Servicios..."
                                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-,\.&]+$"
                                title="Solo se permiten letras, espacios, guiones, comas, puntos y ampersand"
                                maxlength="100"
                                required>
                            </div>
                            @error('rubro')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Teléfono --}}
                          <div class="col-md-6">
                            <label for="telefono" class="form-label">
                              Teléfono <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-phone"></i></span>
                              <input
                                type="tel"
                                class="form-control @error('telefono') is-invalid @enderror"
                                id="telefono"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                placeholder="+56912345678"
                                pattern="^[\+\-\(\)\d\s]+$"
                                title="Solo se permiten números, espacios, guiones, paréntesis y el símbolo +"
                                maxlength="20"
                                required>
                            </div>
                            @error('telefono')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Dirección --}}
                          <div class="col-12">
                            <label for="direccion" class="form-label">
                              Dirección <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-map-pin"></i></span>
                              <textarea
                                class="form-control @error('direccion') is-invalid @enderror"
                                id="direccion"
                                name="direccion"
                                rows="3"
                                placeholder="Ingrese la dirección completa..."
                                pattern=".*\S+.*"
                                title="No puede contener solo espacios en blanco"
                                maxlength="500"
                                required>{{ old('direccion') }}</textarea>
                            </div>
                            @error('direccion')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Nombre Contacto --}}
                          <div class="col-md-6">
                            <label for="nombre_contacto" class="form-label">
                              Nombre del Contacto <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-user"></i></span>
                              <input
                                type="text"
                                class="form-control @error('nombre_contacto') is-invalid @enderror"
                                id="nombre_contacto"
                                name="nombre_contacto"
                                value="{{ old('nombre_contacto') }}"
                                placeholder="Juan Pérez"
                                pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                                title="Solo se permiten letras y espacios"
                                maxlength="255"
                                required>
                            </div>
                            @error('nombre_contacto')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>

                          {{-- Email Contacto --}}
                          <div class="col-md-6">
                            <label for="email_contacto" class="form-label">
                              Email del Contacto <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                              <span class="input-group-text"><i class="ti ti-mail"></i></span>
                              <input
                                type="email"
                                class="form-control @error('email_contacto') is-invalid @enderror"
                                id="email_contacto"
                                name="email_contacto"
                                value="{{ old('email_contacto') }}"
                                placeholder="contacto@empresa.com"
                                pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                title="Debe tener formato válido: ejemplo@dominio.com"
                                maxlength="255"
                                required>
                            </div>
                            @error('email_contacto')
                              <div class="form-text text-danger">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="row">
                          <div class="col-12 d-flex justify-content-between">
                            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                              <i class="ti ti-arrow-left me-1"></i>
                              Volver a Lista
                            </a>
                            <button type="submit" class="btn btn-primary">
                              <i class="ti ti-plus me-1"></i>
                              Agregar Cliente
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
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
                    , VentasFix - Sistema de Gestión Empresarial
                  </div>
                  <div class="d-none d-lg-inline-block">
                    <a href="#" class="footer-link me-4">Licencia</a>
                    <a href="#" target="_blank" class="footer-link me-4">Más Temas</a>
                    <a href="#" target="_blank" class="footer-link me-4">Documentación</a>
                    <a href="#" target="_blank" class="footer-link">Soporte</a>
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

    <!-- Page JS -->
    <script>
      $(document).ready(function() {
        // Configuración para silenciar advertencias
        $.fn.dataTable.ext.errMode = 'none';

        // Función para validar RUT chileno
        function validarRUT(rut) {
          rut = rut.replace(/\./g, '').replace(/\-/g, '');
          if (rut.length < 8 || rut.length > 9) return false;
          
          let cuerpo = rut.slice(0, -1);
          let dv = rut.slice(-1).toUpperCase();
          
          if (!/^\d+$/.test(cuerpo)) return false;
          
          let suma = 0;
          let multiplo = 2;
          
          for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo.charAt(i)) * multiplo;
            multiplo = multiplo < 7 ? multiplo + 1 : 2;
          }
          
          let dvCalculado = 11 - (suma % 11);
          if (dvCalculado === 11) dvCalculado = '0';
          else if (dvCalculado === 10) dvCalculado = 'K';
          else dvCalculado = dvCalculado.toString();
          
          return dvCalculado === dv;
        }

        // Función para validar que solo contenga letras, espacios y caracteres permitidos para rubro
        function validarRubro(texto) {
          const pattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-,\.&]+$/;
          return pattern.test(texto);
        }

        // Función para validar que solo contenga letras y espacios para nombre de contacto
        function validarNombreContacto(texto) {
          const pattern = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
          return pattern.test(texto);
        }

        // Función para validar teléfono (solo números, espacios, guiones, paréntesis y +)
        function validarTelefono(telefono) {
          const pattern = /^[\+\-\(\)\d\s]+$/;
          return pattern.test(telefono);
        }

        // Función para validar formato de email específico
        function validarEmail(email) {
          const pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
          return pattern.test(email);
        }

        // Validación de RUT en tiempo real
        $('#rut_empresa').on('input', function() {
          let rut = $(this).val().trim();
          if (rut && !validarRUT(rut)) {
            $(this).addClass('is-invalid');
          } else {
            $(this).removeClass('is-invalid');
          }
        });

        // Validación de rubro en tiempo real
        $('#rubro').on('input', function() {
          let valor = $(this).val();
          if (valor && !validarRubro(valor)) {
            $(this).addClass('is-invalid');
            $(this).attr('title', 'Solo se permiten letras, espacios, guiones, comas, puntos y ampersand');
          } else {
            $(this).removeClass('is-invalid');
            $(this).attr('title', 'Solo se permiten letras, espacios, guiones, comas, puntos y ampersand');
          }
        });

        // Validación de nombre de contacto en tiempo real
        $('#nombre_contacto').on('input', function() {
          let valor = $(this).val();
          if (valor && !validarNombreContacto(valor)) {
            $(this).addClass('is-invalid');
            $(this).attr('title', 'Solo se permiten letras y espacios');
          } else {
            $(this).removeClass('is-invalid');
            $(this).attr('title', 'Solo se permiten letras y espacios');
          }
        });

        // Validación de teléfono en tiempo real
        $('#telefono').on('input', function() {
          let valor = $(this).val();
          if (valor && !validarTelefono(valor)) {
            $(this).addClass('is-invalid');
            $(this).attr('title', 'Solo se permiten números, espacios, guiones, paréntesis y el símbolo +');
          } else {
            $(this).removeClass('is-invalid');
            $(this).attr('title', 'Solo se permiten números, espacios, guiones, paréntesis y el símbolo +');
          }
        });

        // Validación de email en tiempo real
        $('#email_contacto').on('input', function() {
          let valor = $(this).val();
          if (valor && !validarEmail(valor)) {
            $(this).addClass('is-invalid');
            $(this).attr('title', 'Debe tener formato válido: ejemplo@dominio.com');
          } else {
            $(this).removeClass('is-invalid');
            $(this).attr('title', 'Debe tener formato válido: ejemplo@dominio.com');
          }
        });

        // Validación de campos no vacíos (sin solo espacios)
        $('input[type="text"], input[type="tel"], textarea').not('#rut_empresa').on('blur', function() {
          let valor = $(this).val();
          if (valor && valor.trim() === '') {
            $(this).addClass('is-invalid');
            $(this).val(''); // Limpiar espacios
          } else {
            $(this).removeClass('is-invalid');
          }
        });

        {{-- Script de validación para el formulario de clientes --}}
        $('form').on('submit', function(e) {
          let isValid = true;
          
          // Validar RUT
          let rut = $('#rut_empresa').val().trim();
          if (!rut || !validarRUT(rut)) {
            $('#rut_empresa').addClass('is-invalid');
            isValid = false;
          }
          
          // Validar campos requeridos (no solo espacios)
          $('input[required], textarea[required]').each(function() {
            let valor = $(this).val();
            if (!valor || valor.trim() === '') {
              isValid = false;
              $(this).addClass('is-invalid');
            } else {
              $(this).removeClass('is-invalid');
            }
          });
          
          // Validar formato de email
          const email = $('#email_contacto').val();
          if (email && !validarEmail(email)) {
            isValid = false;
            $('#email_contacto').addClass('is-invalid');
          }
          
          // Validar formato de teléfono
          const telefono = $('#telefono').val();
          if (telefono && !validarTelefono(telefono)) {
            isValid = false;
            $('#telefono').addClass('is-invalid');
          }
          
          // Validar formato de rubro
          const rubro = $('#rubro').val();
          if (rubro && !validarRubro(rubro)) {
            isValid = false;
            $('#rubro').addClass('is-invalid');
          }
          
          // Validar formato de nombre de contacto
          const nombreContacto = $('#nombre_contacto').val();
          if (nombreContacto && !validarNombreContacto(nombreContacto)) {
            isValid = false;
            $('#nombre_contacto').addClass('is-invalid');
          }
          
          if (!isValid) {
            e.preventDefault();
            // Mostrar mensaje de error
            if (!$('.alert-danger').length) {
              const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="ti ti-alert-triangle me-2"></i>
                  <strong>¡Error!</strong> Por favor, complete todos los campos requeridos correctamente.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              `;
              $('.container-xxl.flex-grow-1').prepend(alertHtml);
            }
            // Scroll al primer error
            $('html, body').animate({
              scrollTop: $('.is-invalid:first').offset().top - 100
            }, 300);
          }
        });
        
        // Limpiar validación al escribir
        $('input, textarea').on('input', function() {
          if ($(this).val().trim()) {
            $(this).removeClass('is-invalid');
          }
        });
        
        // Auto-limpiar mensajes después del submit exitoso
        @if(session('success'))
          setTimeout(function() {
            // Limpiar formulario después de crear exitosamente
            $('form')[0].reset();
          }, 2000);
        @endif
      });
    </script>
  </body>
</html>