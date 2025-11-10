# API de Gestión de Vehículos

API RESTful desarrollada con FastAPI siguiendo arquitectura hexagonal y principios SOLID para gestionar marcas de vehículos, personas y su relación de propiedad con vehículos.

## 🏗️ Arquitectura

El proyecto sigue una **arquitectura hexagonal** (puertos y adaptadores) con las siguientes capas:

- **Domain**: Entidades y interfaces de repositorios (puertos)
- **Application**: Servicios de aplicación (casos de uso)
- **Infrastructure**: Implementaciones de repositorios y modelos ORM (adaptadores)
- **API**: Controladores y rutas REST

## 📋 Requisitos

- Python 3.10+
- PostgreSQL 12+ (o Docker)
- pip

## 🚀 Instalación

1. **Clonar el repositorio** (si aplica)

2. **Instalar dependencias**:
```bash
pip install -r requirements.txt
```

3. **Configurar variables de entorno**:
```bash
# Copiar el archivo de ejemplo
cp .env.example .env

# Editar .env con tus credenciales de PostgreSQL
# Windows (PowerShell):
# Copy-Item .env.example .env
# notepad .env
```

4. **Configurar PostgreSQL**:

   **Opción A: Usando Docker (Recomendado)**
   ```bash
   docker compose up -d
   ```
   Esto levantará PostgreSQL y la API automáticamente.

   **Opción B: PostgreSQL local**
   - Asegúrate de tener PostgreSQL instalado y corriendo
   - Crea la base de datos manualmente si no existe

5. **Inicializar la base de datos**:
   
   Las tablas se crean automáticamente al iniciar la aplicación. Si necesitas crearlas manualmente, asegúrate de que la base de datos exista y ejecuta la aplicación una vez.

## ⚙️ Configuración

### Variables de Entorno

La aplicación utiliza un archivo `.env` para configurar las credenciales de la base de datos. 

1. **Copia el archivo de ejemplo**:
   ```bash
   cp .env.example .env
   ```

2. **Edita `.env`** con tus credenciales de PostgreSQL:
   ```env
   POSTGRES_HOST=localhost
   POSTGRES_PORT=5432
   POSTGRES_USER=tu_usuario
   POSTGRES_PASSWORD=tu_contraseña
   POSTGRES_DB=nombre_base_datos
   ```

3. **Variables disponibles**:
   - `POSTGRES_HOST`: Host de PostgreSQL (default: localhost)
   - `POSTGRES_PORT`: Puerto de PostgreSQL (default: 5432)
   - `POSTGRES_USER`: Usuario de PostgreSQL (default: icanh)
   - `POSTGRES_PASSWORD`: Contraseña de PostgreSQL (default: 123456)
   - `POSTGRES_DB`: Nombre de la base de datos (default: icanh_vehiculos_db)
   - `TEST_POSTGRES_*`: Variables opcionales para pruebas (usan los valores de arriba por defecto)

**Nota**: Si no defines un archivo `.env`, la aplicación usará los valores por defecto definidos en `app/core/config.py`. La conexión se construye automáticamente usando psycopg2.

## 🏃 Ejecutar la aplicación

```bash
uvicorn main:app --reload
```

La API estará disponible en: `http://localhost:8000`

- **Documentación Swagger**: `http://localhost:8000/docs`
- **Documentación ReDoc**: `http://localhost:8000/redoc`

## 🧪 Ejecutar pruebas

### Pruebas Automatizadas

```bash
pytest
```

Para ejecutar con más detalles:
```bash
pytest -v
```

Para ejecutar un test específico:
```bash
pytest tests/test_api.py::test_crear_marca
```

Las pruebas se ejecutan contra PostgreSQL. Asegúrate de que la base de datos esté disponible o configura `TEST_DATABASE_URL` en las variables de entorno.

**Cobertura de tests:**
- ✅ CRUD completo para Marcas, Personas y Vehículos
- ✅ Validaciones (duplicados, campos requeridos)
- ✅ Relaciones (propietarios, vehículos por persona)
- ✅ Casos de error (404, 400)
- ✅ Flujo completo end-to-end

### Pruebas con Postman

Para pruebas manuales, importa la colección de Postman:

1. Abre Postman
2. Importa el archivo `postman_collection.json`
3. Configura la variable `base_url` si es necesario (default: `http://localhost:8000`)
4. Sigue el flujo de prueba recomendado en `POSTMAN_README.md`

La colección incluye todos los endpoints organizados por categorías.

## 📚 Endpoints

### Marcas
- `POST /api/marcas/` - Crear marca
- `GET /api/marcas/` - Listar marcas
- `GET /api/marcas/{id}` - Obtener marca
- `PUT /api/marcas/{id}` - Actualizar marca
- `DELETE /api/marcas/{id}` - Eliminar marca

### Personas
- `POST /api/personas/` - Crear persona
- `GET /api/personas/` - Listar personas
- `GET /api/personas/{id}` - Obtener persona
- `PUT /api/personas/{id}` - Actualizar persona
- `DELETE /api/personas/{id}` - Eliminar persona
- `GET /api/personas/{id}/vehiculos/` - Obtener vehículos de una persona

### Vehículos
- `POST /api/vehiculos/` - Crear vehículo
- `GET /api/vehiculos/` - Listar vehículos
- `GET /api/vehiculos/{id}` - Obtener vehículo
- `PUT /api/vehiculos/{id}` - Actualizar vehículo
- `DELETE /api/vehiculos/{id}` - Eliminar vehículo
- `POST /api/vehiculos/{id}/propietarios/` - Asignar propietario a vehículo

## 🐳 Docker

Para ejecutar todo con Docker:

```bash
docker compose up -d
```

Esto levantará:
- PostgreSQL en el puerto 5432
- La API en el puerto 8000

Para detener:
```bash
docker compose down
```

## 🔧 Solución de problemas

### Error: `psycopg2.OperationalError`

**Causas comunes:**
1. PostgreSQL no está corriendo
2. La base de datos no existe
3. Credenciales incorrectas
4. Puerto bloqueado

**Soluciones:**
1. Verifica que PostgreSQL esté corriendo:
   ```bash
   # Con Docker
   docker compose ps
   
   # Localmente (Windows)
   # Verifica en Servicios de Windows
   ```

2. Crea la base de datos manualmente si no existe:
   ```sql
   CREATE DATABASE icanh_vehiculos_db;
   ```

3. Verifica las credenciales en tu archivo `.env` o en `app/core/config.py`

4. Asegúrate de que las variables de entorno estén correctamente configuradas:
   - `POSTGRES_HOST`
   - `POSTGRES_PORT`
   - `POSTGRES_USER`
   - `POSTGRES_PASSWORD`
   - `POSTGRES_DB`

### Error al crear tablas

Las tablas se crean automáticamente al iniciar la aplicación. Si no se crean:
1. Verifica que la base de datos exista
2. Verifica que las credenciales sean correctas
3. Revisa los logs de la aplicación para ver errores específicos

## 📁 Estructura del proyecto

```
fastApiProject/
├── app/
│   ├── api/              # Capa de presentación (REST)
│   │   ├── routes/       # Rutas de la API
│   │   └── schemas.py    # Esquemas Pydantic
│   ├── application/      # Capa de aplicación
│   │   └── services/     # Servicios de negocio
│   ├── domain/           # Capa de dominio
│   │   ├── entities.py  # Entidades de dominio
│   │   └── repositories.py  # Interfaces de repositorios
│   ├── infrastructure/  # Capa de infraestructura
│   │   └── db/
│   │       ├── models/   # Modelos ORM (separados por entidad)
│   │       ├── repositories/  # Implementaciones de repositorios
│   │       ├── base.py   # Base para modelos
│   │       └── session.py  # Configuración de sesión
│   └── core/             # Configuración central
│       └── config.py     # Configuración de la aplicación
├── tests/               # Pruebas automatizadas
├── main.py              # Punto de entrada
├── requirements.txt     # Dependencias
├── Dockerfile          # Imagen Docker
└── docker-compose.yml  # Orquestación Docker
```

## 🎯 Principios aplicados

- **SOLID**: Separación de responsabilidades, inversión de dependencias
- **Arquitectura Hexagonal**: Aislamiento del dominio de la infraestructura
- **Clean Code**: Código legible y mantenible
- **DRY**: Evitar duplicación de código

## 📝 Licencia

Este proyecto es parte de una prueba técnica.

