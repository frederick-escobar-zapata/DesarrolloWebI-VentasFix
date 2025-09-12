# 🏢 VentasFix - Sistema de Backoffice

**VentasFix** es un sistema completo de backoffice desarrollado en Laravel 11 con API RESTful y autenticación mediante Laravel Sanctum. Diseñado para la gestión administrativa de usuarios, productos y clientes empresariales.

## 🚀 Características Principales

- ✅ **API RESTful completa** con autenticación JWT
- ✅ **Service Layer Pattern** para lógica de negocio reutilizable
- ✅ **Laravel Sanctum** para autenticación API y Web
- ✅ **CRUD completo** para Usuarios, Productos y Clientes
- ✅ **Validaciones empresariales** (RUT, precios con IVA automático)
- ✅ **Naming conventions en español**
- ✅ **Token expiration** configurable (15 minutos por defecto)
- ✅ **Manejo de errores profesional** con respuestas JSON

## 🛠️ Tecnologías

- **Backend**: Laravel 11.46.0
- **Base de datos**: MySQL
- **Autenticación**: Laravel Sanctum
- **Arquitectura**: Service Layer Pattern
- **API**: RESTful JSON

## 📋 Requisitos

- PHP 8.1+
- Composer
- MySQL 5.7+
- Laravel 11.x

## 🔧 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/frederick-escobar-zapata/DesarrolloWebI-VentasFix.git
cd VentasFix
```

### 2. Instalar dependencias
```bash
composer install
```

### 3. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos
Edita el archivo `.env` con tu configuración de base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ventasfix
DB_USERNAME=*****
DB_PASSWORD=**********

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed --class=UsuarioPruebaSeeder
```

### 6. Instalar Laravel Sanctum
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 7. Iniciar el servidor
```bash
php artisan serve
```

El servidor estará disponible en `http://127.0.0.1:8000`

## 🔐 Autenticación

### Usuario de Prueba
```
Email: frederick.escobar@ventasfix.com
Password: password
```

### Proceso de Autenticación
1. **Login**: `POST /api/auth/login` → Obtienes un token
2. **Usar token**: Agregar header `Authorization: Bearer {token}` en peticiones
3. **Token expiration**: Los tokens expiran en 15 minutos
4. **Logout**: `POST /api/auth/logout` para revocar el token actual

## 📚 API Endpoints

### Autenticación
| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| `POST` | `/api/auth/login` | Iniciar sesión | ❌ Pública |
| `POST` | `/api/auth/logout` | Cerrar sesión | ✅ Requerida |
| `POST` | `/api/auth/revocar-tokens` | Revocar todos los tokens | ✅ Requerida |

### Usuarios
| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| `GET` | `/api/usuarios` | Listar usuarios | ✅ Requerida |
| `POST` | `/api/usuarios` | Crear usuario | ✅ Requerida |
| `GET` | `/api/usuarios/{id}` | Obtener usuario | ✅ Requerida |
| `PUT` | `/api/usuarios/{id}` | Actualizar usuario | ✅ Requerida |
| `DELETE` | `/api/usuarios/{id}` | Eliminar usuario | ✅ Requerida |

### Productos
| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| `GET` | `/api/productos` | Listar productos | ✅ Requerida |
| `POST` | `/api/productos` | Crear producto | ✅ Requerida |
| `GET` | `/api/productos/{id}` | Obtener producto | ✅ Requerida |
| `PUT` | `/api/productos/{id}` | Actualizar producto | ✅ Requerida |
| `DELETE` | `/api/productos/{id}` | Eliminar producto | ✅ Requerida |

### Clientes
| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| `GET` | `/api/clientes` | Listar clientes | ✅ Requerida |
| `POST` | `/api/clientes` | Crear cliente | ✅ Requerida |
| `GET` | `/api/clientes/{id}` | Obtener cliente | ✅ Requerida |
| `PUT` | `/api/clientes/{id}` | Actualizar cliente | ✅ Requerida |
| `DELETE` | `/api/clientes/{id}` | Eliminar cliente | ✅ Requerida |

## 📝 Ejemplos de Uso

### 1. Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "frederick.escobar@ventasfix.com",
    "password": "password"
  }'
```

### 2. Listar Usuarios (con token)
```bash
curl -X GET http://127.0.0.1:8000/api/usuarios \
  -H "Authorization: Bearer {tu_token_aqui}" \
  -H "Content-Type: application/json"
```

### 3. Crear Producto
```bash
curl -X POST http://127.0.0.1:8000/api/productos \
  -H "Authorization: Bearer {tu_token_aqui}" \
  -H "Content-Type: application/json" \
  -d '{
    "codigo": "PROD001",
    "nombre": "Laptop Dell",
    "descripcion": "Laptop empresarial",
    "precio_neto": 500000,
    "stock": 10
  }'
```

## 🏗️ Arquitectura

### Service Layer Pattern
El proyecto utiliza Service Layer Pattern para separar la lógica de negocio:

```
app/
├── Http/Controllers/Api/     # Controladores API
├── Services/                 # Lógica de negocio
│   ├── UsuarioServicio.php
│   ├── ProductoServicio.php
│   └── ClienteServicio.php
└── Models/                   # Modelos Eloquent
```

### Funcionalidades por Servicio

**UsuarioServicio:**
- Validación de RUT chileno
- Validación de email único
- Encriptación de contraseñas

**ProductoServicio:**
- Cálculo automático de precio con IVA (19%)
- Validación de stock
- Códigos únicos de producto

**ClienteServicio:**
- Validaciones empresariales
- Gestión de datos de contacto

## 🛡️ Seguridad

- ✅ **Laravel Sanctum** para autenticación API
- ✅ **Token expiration** (15 minutos)
- ✅ **Password hashing** con bcrypt
- ✅ **Validación de entrada** en todos los endpoints
- ✅ **CORS** configurado
- ✅ **Middleware de autenticación** en rutas protegidas

## 🔧 Configuración Avanzada

### Cambiar tiempo de expiración del token
En `config/sanctum.php`:
```php
'expiration' => 15, // minutos
```

### Configurar CORS
En `config/cors.php` puedes ajustar las configuraciones de CORS según tus necesidades.

## 📦 Estructura del Proyecto

```
VentasFix/
├── app/
│   ├── Http/Controllers/Api/   # Controladores API
│   ├── Models/                 # Modelos Eloquent
│   ├── Services/              # Service Layer
│   └── Http/Middleware/       # Middleware personalizado
├── database/
│   ├── migrations/            # Migraciones de base de datos
│   └── seeders/              # Seeders de datos
├── routes/
│   ├── api.php               # Rutas API
│   └── web.php               # Rutas Web
└── config/                   # Archivos de configuración
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**Frederick Escobar Zapata**
- GitHub: [@frederick-escobar-zapata](https://github.com/frederick-escobar-zapata)
- Email: frederick.escobar@ventasfix.com

## 🚀 Próximas Funcionalidades

- [ ] Interfaz Web con Dashboard Administrativo
- [ ] Sistema de Roles y Permisos
- [ ] Módulo de Ventas y Facturación
- [ ] Reportes y Analytics
- [ ] API de Inventario Avanzado

---

⭐ Si este proyecto te fue útil, ¡no olvides darle una estrella en GitHub!
