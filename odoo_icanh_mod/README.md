# Módulo Odoo 18 - Gestión de Vehículos ICANH

Módulo completo para Odoo 18 que permite gestionar marcas de vehículos, personas y vehículos con API REST.

![image.png](static%2Fdescription%2Fassets%2Ficons%2Fimage.png)
## 📋 Características

- **3 Modelos principales**:
  - Marcas de Vehículos (`icanh.marca.vehiculo`)
  - Personas (`icanh.persona`)
  - Vehículos (`icanh.vehiculo`)

- **Vistas completas**:
  - Vista Tree (Lista)
  - Vista Form (Formulario)
  - Vista Kanban (Tarjetas)
  - Vista Search (Búsqueda)

- **API REST**:
  - Prefijo: `/api/icanh`
  - CRUD completo para todas las entidades
  - Autenticación pública (`auth='public'`) - No requiere autenticación
  - Soporte CORS

## 🚀 Instalación

1. **Copiar el módulo a Odoo**:
   ```bash
   cp -r odoo_icanh_mod /ruta/a/odoo/addons/
   ```

2. **Actualizar la lista de aplicaciones**:
   - En Odoo, ve a **Aplicaciones**
   - Click en **Actualizar lista de aplicaciones**

3. **Instalar el módulo**:
   - Busca "Gestión de Vehículos ICANH"
   - Click en **Instalar**

## 📁 Estructura del Módulo

```
odoo_icanh_mod/
├── __init__.py
├── __manifest__.py
├── models/
│   ├── __init__.py
│   ├── marca_vehiculo.py
│   ├── persona.py
│   └── vehiculo.py
├── controllers/
│   ├── __init__.py
│   └── api_controller.py
├── views/
│   ├── marca_vehiculo_views.xml
│   ├── persona_views.xml
│   ├── vehiculo_views.xml
│   └── menu_views.xml
└── security/
    └── ir.model.access.csv
```

## 🎯 Modelos

### Marca de Vehículo
- `nombre_marca`: Nombre de la marca (requerido, único)
- `pais`: País de origen (requerido)
- `vehiculo_ids`: Relación One2many con vehículos
- `vehiculo_count`: Contador de vehículos

### Persona
- `nombre`: Nombre completo (requerido)
- `cedula`: Cédula de identidad (requerido, único)
- `vehiculo_ids`: Relación Many2many con vehículos (propietarios)
- `vehiculo_count`: Contador de vehículos

### Vehículo
- `modelo`: Modelo del vehículo (requerido)
- `marca_id`: Relación Many2one con marca (requerido)
- `numero_puertas`: Número de puertas (requerido, 2-6)
- `color`: Color del vehículo (requerido)
- `propietario_ids`: Relación Many2many con personas
- `propietario_count`: Contador de propietarios

## 🌐 API REST

### Prefijo Base
```
/api/icanh
```

### Endpoints Disponibles

#### Marcas
- `GET /api/icanh/marcas` - Listar todas las marcas
- `GET /api/icanh/marcas/{id}` - Obtener una marca
- `POST /api/icanh/marcas` - Crear marca
- `PUT /api/icanh/marcas/{id}` - Actualizar marca
- `DELETE /api/icanh/marcas/{id}` - Eliminar marca

#### Personas
- `GET /api/icanh/personas` - Listar todas las personas
- `GET /api/icanh/personas/{id}` - Obtener una persona
- `POST /api/icanh/personas` - Crear persona
- `PUT /api/icanh/personas/{id}` - Actualizar persona
- `DELETE /api/icanh/personas/{id}` - Eliminar persona
- `GET /api/icanh/personas/{id}/vehiculos` - Obtener vehículos de una persona

#### Vehículos
- `GET /api/icanh/vehiculos` - Listar todos los vehículos
- `GET /api/icanh/vehiculos/{id}` - Obtener un vehículo
- `POST /api/icanh/vehiculos` - Crear vehículo
- `PUT /api/icanh/vehiculos/{id}` - Actualizar vehículo
- `DELETE /api/icanh/vehiculos/{id}` - Eliminar vehículo
- `POST /api/icanh/vehiculos/{id}/propietarios` - Agregar propietario

### Ejemplos de Uso

#### Crear una Marca
```bash
curl -X POST http://localhost:8069/api/icanh/marcas \
  -H "Content-Type: application/json" \
  -d '{
    "nombre_marca": "Toyota",
    "pais": "Japón"
  }'
```

#### Crear una Persona
```bash
curl -X POST http://localhost:8069/api/icanh/personas \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez",
    "cedula": "1234567890"
  }'
```

#### Crear un Vehículo
```bash
curl -X POST http://localhost:8069/api/icanh/vehiculos \
  -H "Content-Type: application/json" \
  -d '{
    "modelo": "Corolla",
    "marca_id": 1,
    "numero_puertas": 4,
    "color": "Rojo",
    "propietarios_ids": [1]
  }'
```

## 📱 Vistas

### Vista Tree
- Lista todos los registros en formato tabla
- Muestra campos principales y contadores
- Decoración visual para registros con relaciones

### Vista Form
- Formulario completo para crear/editar
- Pestañas para relaciones (vehículos, propietarios)
- Validaciones en tiempo real

### Vista Kanban
- Visualización en tarjetas
- Agrupación por campos relevantes (marca, país)
- Iconos y contadores visuales

## 🔒 Seguridad

El módulo incluye permisos de acceso básicos:
- Todos los usuarios pueden leer, escribir, crear y eliminar
- Configurable en `security/ir.model.access.csv`

## 📝 Notas

- El módulo usa `auth='public'` para la API, lo que significa que usa el usuario público (guest) de Odoo y no requiere autenticación
- Los endpoints son accesibles públicamente sin necesidad de cookies o tokens de sesión
- Para producción, considera agregar autenticación adicional más robusta (API keys, tokens, etc.)
- Los endpoints soportan CORS para acceso desde otros dominios
- Las validaciones de negocio están en los modelos Python

## 🆘 Solución de Problemas

### El módulo no aparece en la lista
- Verifica que esté en la carpeta `addons` de Odoo
- Asegúrate de que `__manifest__.py` esté correcto
- Actualiza la lista de aplicaciones

### Error al instalar
- Verifica que todas las dependencias estén instaladas
- Revisa los logs de Odoo para más detalles

### La API no responde
- Verifica que el servidor Odoo esté corriendo
- Revisa que las rutas estén correctamente registradas
- Verifica los logs de Odoo

## 👤 Autor

DUMAR PABON

## 📄 Licencia

Este proyecto es parte de una prueba técnica.

