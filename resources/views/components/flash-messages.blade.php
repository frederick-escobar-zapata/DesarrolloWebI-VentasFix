{{-- 
  Componente reutilizable para mostrar mensajes flash del sistema
  
  Este componente centraliza la visualización de todos los tipos de mensajes
  temporales que se almacenan en la sesión de Laravel. Incluye:
  
  - Mensajes de éxito (success): Operaciones completadas correctamente
  - Mensajes de error (error): Fallos o excepciones del sistema  
  - Mensajes de advertencia (warning): Situaciones que requieren atención
  - Mensajes informativos (info): Información general para el usuario
  
  Cada tipo incluye:
  - Icono apropiado para identificación visual rápida
  - Colores diferenciados según Bootstrap
  - Botón de cerrar para mejor UX
  - Accesibilidad con roles ARIA
  - Animación fade para entrada/salida
--}}

{{-- MENSAJES DE ÉXITO: Operaciones completadas exitosamente --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="ti ti-check-circle me-2"></i>
    <strong>¡Éxito!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

{{-- MENSAJES DE ERROR: Fallos del sistema o validaciones --}}
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="ti ti-alert-circle me-2"></i>
    <strong>Error:</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

{{-- MENSAJES DE ADVERTENCIA: Situaciones que requieren atención --}}
@if(session('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="ti ti-alert-triangle me-2"></i>
    <strong>Advertencia:</strong> {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

{{-- MENSAJES INFORMATIVOS: Información general para el usuario --}}
@if(session('info'))
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="ti ti-info-circle me-2"></i>
    <strong>Info:</strong> {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif