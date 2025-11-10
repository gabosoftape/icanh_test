# API Gestión de Vehículos - Laravel

API RESTful para gestionar marcas de vehículos, personas y su relación de propiedad con vehículos, implementada con arquitectura hexagonal y principios SOLID.

## 🏗️ Arquitectura

Este proyecto sigue una **Arquitectura Hexagonal (Ports and Adapters)**:

- **Domain**: Entidades de dominio puras e interfaces de repositorios
- **Application**: Servicios de aplicación con lógica de negocio
- **Infrastructure**: Implementaciones de repositorios (Eloquent), modelos de base de datos
- **API**: Controladores, Requests, Resources

## 📋 Requisitos

- PHP 8.2+
- Composer
- PostgreSQL 15+
- Node.js y NPM (para frontend)

## 🚀 Instalación

### Opción 1: Sin Docker

```bash
# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=password

# Ejecutar migraciones
php artisan migrate

# Generar documentación Swagger
php artisan l5-swagger:generate

# Correr backend
php artisan serve --port=9000
```

### Opción 2: Con Docker

```bash
# Construir y levantar contenedores
docker-compose up -d --build

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Generar documentación Swagger
docker-compose exec app php artisan l5-swagger:generate
```

## 🧪 Tests

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter MarcaTest
```

## 📚 Documentación

### Swagger/OpenAPI

La documentación interactiva está disponible en:
- **Swagger UI**: `http://localhost:8000/api/documentation`
- **JSON**: `http://localhost:8000/docs/api-docs.json`

Para regenerar la documentación:
```bash
php artisan l5-swagger:generate
```

### phpDocumentor

Para generar documentación del código fuente:

```bash
# Instalar phpDocumentor
composer require --dev phpdocumentor/phpdocumentor

# Generar documentación
vendor/bin/phpdoc -d app -t build/api-docs
```

La documentación se generará en `build/api-docs/`.

## 🔌 Endpoints API

### Marcas
- `GET /api/marcas` - Listar marcas
- `POST /api/marcas` - Crear marca
- `GET /api/marcas/{id}` - Obtener marca
- `PUT /api/marcas/{id}` - Actualizar marca
- `DELETE /api/marcas/{id}` - Eliminar marca

### Personas
- `GET /api/personas` - Listar personas
- `POST /api/personas` - Crear persona
- `GET /api/personas/{id}` - Obtener persona
- `PUT /api/personas/{id}` - Actualizar persona
- `DELETE /api/personas/{id}` - Eliminar persona
- `GET /api/personas/{id}/vehiculos` - Vehículos de una persona

### Vehículos
- `GET /api/vehiculos` - Listar vehículos
- `POST /api/vehiculos` - Crear vehículo
- `GET /api/vehiculos/{id}` - Obtener vehículo
- `PUT /api/vehiculos/{id}` - Actualizar vehículo
- `DELETE /api/vehiculos/{id}` - Eliminar vehículo
- `POST /api/vehiculos/{id}/propietarios` - Asignar propietario

## 🏛️ Principios SOLID Aplicados

- **S**ingle Responsibility: Cada clase tiene una única responsabilidad
- **O**pen/Closed: Abierto para extensión, cerrado para modificación
- **L**iskov Substitution: Las implementaciones cumplen los contratos de las interfaces
- **I**nterface Segregation: Interfaces específicas y pequeñas
- **D**ependency Inversion: Dependencias hacia abstracciones, no implementaciones

## 📁 Estructura del Proyecto

```
app/
├── Domain/              # Capa de dominio
│   ├── Entities/        # Entidades de negocio
│   └── Repositories/    # Interfaces de repositorios
├── Application/         # Capa de aplicación
│   └── Services/        # Servicios de negocio
├── Infrastructure/       # Capa de infraestructura
│   ├── Eloquent/        # Modelos Eloquent
│   └── Repositories/    # Implementaciones de repositorios
└── Http/                # Capa de presentación
    ├── Controllers/     # Controladores API
    ├── Requests/       # Validación de requests
    └── Resources/      # Transformación de respuestas
```

## 🐳 Docker

El proyecto incluye:
- **Dockerfile**: Imagen PHP-FPM con extensiones necesarias
- **docker-compose.yml**: Orquestación de servicios (app, nginx, postgres)
- **docker/nginx/default.conf**: Configuración de Nginx

## 📝 Licencia

Este proyecto es parte de una prueba técnica.


