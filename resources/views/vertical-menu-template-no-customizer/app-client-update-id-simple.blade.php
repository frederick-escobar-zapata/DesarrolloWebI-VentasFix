<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cliente por ID - VentasFix</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Public Sans', sans-serif;
        }
        .main-container {
            width: 100%;
            max-width: none;
            padding: 20px;
        }
        .search-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .client-info-card {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-card {
            background: white;
            border: 1px solid #ff9800;
            border-radius: 8px;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-warning:hover {
            background-color: #f57c00;
            border-color: #f57c00;
        }
        .form-control:focus {
            border-color: #ff9800;
            box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="text-primary">
                <i class="bi bi-arrow-left-right me-2"></i>
                VentasFix - Actualizar Cliente por ID
            </h2>
            <p class="text-muted">Busque y actualice los datos de un cliente específico</p>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="search-card p-4">
            <h5 class="mb-3">
                <i class="bi bi-search me-2"></i>
                Buscar Cliente por ID
            </h5>
            <form method="GET" action="{{ route('clientes.actualizar-por-id') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="id" class="form-label">ID del Cliente</label>
                        <input type="number" 
                               class="form-control" 
                               id="id" 
                               name="id" 
                               value="{{ request('id') }}"
                               placeholder="Ingrese el ID del cliente..." 
                               min="1" 
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-search me-2"></i>Buscar Cliente
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($mensaje) && !empty($mensaje) && !session('success') && !session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ $mensaje }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($cliente) && $cliente)
            <!-- Información del Cliente -->
            <div class="client-info-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px; font-size: 18px; font-weight: bold;">
                        {{ strtoupper(substr($cliente->nombre_contacto ?? 'C', 0, 1)) }}{{ strtoupper(substr($cliente->razon_social ?? 'L', 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="mb-1">{{ $cliente->nombre_contacto }} - {{ $cliente->razon_social }}</h5>
                        <small class="text-muted">ID: {{ $cliente->id }}</small>
                    </div>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-2">
                        <strong>RUT:</strong><br>
                        <span class="text-muted">{{ $cliente->rut_empresa }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Email:</strong><br>
                        <span class="text-muted">{{ $cliente->email_contacto }}</span>
                    </div>
                    <div class="col-md-2">
                        <strong>Teléfono:</strong><br>
                        <span class="text-muted">{{ $cliente->telefono }}</span>
                    </div>
                    <div class="col-md-2">
                        <strong>Rubro:</strong><br>
                        <span class="text-muted">{{ $cliente->rubro }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Registrado:</strong><br>
                        <span class="text-muted">{{ $cliente->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Formulario de Actualización -->
            <div class="form-card p-4">
                <h5 class="mb-4 text-warning">
                    <i class="bi bi-pencil-square me-2"></i>
                    Actualizar Datos del Cliente
                </h5>

                <form method="POST" action="{{ route('clientes.actualizar-por-id.post') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $cliente->id }}">

                    <div class="row g-3">
                        <!-- RUT y Razón Social -->
                        <div class="col-md-6">
                            <label for="rut" class="form-label">RUT <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('rut') is-invalid @enderror" 
                                   id="rut" 
                                   name="rut" 
                                   value="{{ old('rut', $cliente->rut_empresa) }}" 
                                   required>
                            @error('rut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="razon_social" class="form-label">Razón Social <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('razon_social') is-invalid @enderror" 
                                   id="razon_social" 
                                   name="razon_social" 
                                   value="{{ old('razon_social', $cliente->razon_social) }}" 
                                   required>
                            @error('razon_social')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email y Teléfono -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $cliente->email_contacto) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                            <input type="tel" 
                                   class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" 
                                   name="telefono" 
                                   value="{{ old('telefono', $cliente->telefono) }}" 
                                   required>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nombre Contacto y Rubro -->
                        <div class="col-md-6">
                            <label for="nombre_contacto" class="form-label">Nombre de Contacto <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nombre_contacto') is-invalid @enderror" 
                                   id="nombre_contacto" 
                                   name="nombre_contacto" 
                                   value="{{ old('nombre_contacto', $cliente->nombre_contacto) }}" 
                                   required>
                            @error('nombre_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="rubro" class="form-label">Rubro <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('rubro') is-invalid @enderror" 
                                   id="rubro" 
                                   name="rubro" 
                                   value="{{ old('rubro', $cliente->rubro) }}" 
                                   required>
                            @error('rubro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección y Estado -->
                        <div class="col-md-8">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" 
                                   class="form-control @error('direccion') is-invalid @enderror" 
                                   id="direccion" 
                                   name="direccion" 
                                   value="{{ old('direccion', $cliente->direccion) }}">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="activo" class="form-label">Estado</label>
                            <select class="form-select @error('activo') is-invalid @enderror" id="activo" name="activo">
                                <option value="1" {{ old('activo', $cliente->activo ?? 1) == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('activo', $cliente->activo ?? 1) == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('activo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-floppy me-2"></i>Actualizar Cliente
                        </button>
                        <a href="{{ route('clientes.actualizar-por-id') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        @elseif(request('id'))
            <!-- Cliente no encontrado -->
            <div class="search-card p-5 text-center">
                <i class="bi bi-person-x text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3">Cliente No Encontrado</h5>
                <p class="text-muted">No se encontró ningún cliente con el ID: <strong>{{ request('id') }}</strong></p>
                <a href="{{ route('clientes.actualizar-por-id') }}" class="btn btn-warning">
                    <i class="bi bi-search me-2"></i>Buscar Otro Cliente
                </a>
            </div>
        @else
            <!-- Estado inicial -->
            <div class="search-card p-5 text-center">
                <i class="bi bi-search text-muted" style="font-size: 64px;"></i>
                <h5 class="mt-3">Actualizar Cliente por ID</h5>
                <p class="text-muted">Ingrese el ID del cliente que desea actualizar en el formulario de búsqueda</p>
                <div class="alert alert-info d-inline-block">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Nota:</strong> Podrá modificar todos los datos del cliente encontrado.
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>