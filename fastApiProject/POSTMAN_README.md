# Colección de Postman - API Gestión de Vehículos

Esta colección de Postman contiene todos los endpoints de la API para facilitar las pruebas manuales.

## 📥 Importar la Colección

1. Abre Postman
2. Haz clic en **Import** (arriba a la izquierda)
3. Selecciona el archivo `postman_collection.json`
4. La colección se importará con todas las carpetas organizadas

## 🔧 Configurar Variables

La colección usa variables para facilitar el uso:

- `base_url`: URL base de la API (default: `http://localhost:8000`)
- `marca_id`: ID de una marca (se actualiza después de crear una)
- `persona_id`: ID de una persona (se actualiza después de crear una)
- `vehiculo_id`: ID de un vehículo (se actualiza después de crear uno)

### Para cambiar la URL base:

1. Haz clic en la colección "API Gestión de Vehículos"
2. Ve a la pestaña **Variables**
3. Modifica el valor de `base_url` si tu API corre en otro puerto

## 📋 Estructura de la Colección

### 🏷️ Marcas
- **Crear Marca**: POST `/api/marcas/`
- **Listar Marcas**: GET `/api/marcas/`
- **Obtener Marca por ID**: GET `/api/marcas/{id}`
- **Actualizar Marca**: PUT `/api/marcas/{id}`
- **Eliminar Marca**: DELETE `/api/marcas/{id}`

### 👤 Personas
- **Crear Persona**: POST `/api/personas/`
- **Listar Personas**: GET `/api/personas/`
- **Obtener Persona por ID**: GET `/api/personas/{id}`
- **Actualizar Persona**: PUT `/api/personas/{id}`
- **Eliminar Persona**: DELETE `/api/personas/{id}`
- **Obtener Vehículos de Persona**: GET `/api/personas/{id}/vehiculos/`

### 🚗 Vehículos
- **Crear Vehículo**: POST `/api/vehiculos/`
- **Listar Vehículos**: GET `/api/vehiculos/`
- **Obtener Vehículo por ID**: GET `/api/vehiculos/{id}`
- **Actualizar Vehículo**: PUT `/api/vehiculos/{id}`
- **Eliminar Vehículo**: DELETE `/api/vehiculos/{id}`
- **Agregar Propietario a Vehículo**: POST `/api/vehiculos/{id}/propietarios/`

### 🔧 Sistema
- **Health Check**: GET `/health`
- **Root**: GET `/`

## 🚀 Flujo de Prueba Recomendado

### 1. Verificar que la API esté funcionando
```
GET /health
```

### 2. Crear una marca
```
POST /api/marcas/
Body: {
    "nombre_marca": "Toyota",
    "pais": "Japón"
}
```
**Nota**: Guarda el `id` de la respuesta para usarlo en los siguientes pasos.

### 3. Crear una persona
```
POST /api/personas/
Body: {
    "nombre": "Juan Pérez",
    "cedula": "123456789"
}
```
**Nota**: Guarda el `id` de la respuesta.

### 4. Crear un vehículo
```
POST /api/vehiculos/
Body: {
    "modelo": "Corolla",
    "marca_id": <id_de_la_marca>,
    "numero_puertas": 4,
    "color": "Rojo"
}
```
**Nota**: Guarda el `id` del vehículo.

### 5. Asignar propietario al vehículo
```
POST /api/vehiculos/<vehiculo_id>/propietarios/
Body: {
    "persona_id": <id_de_la_persona>
}
```

### 6. Verificar la relación
```
GET /api/personas/<persona_id>/vehiculos/
```

## 💡 Tips

1. **Variables dinámicas**: Puedes usar las variables `{{marca_id}}`, `{{persona_id}}`, `{{vehiculo_id}}` en las URLs después de crear los recursos.

2. **Tests automáticos**: Puedes agregar tests en Postman para validar las respuestas automáticamente.

3. **Environments**: Crea diferentes environments (Development, Production) para cambiar fácilmente entre entornos.

4. **Ejemplos de respuestas**: Cada request puede tener ejemplos de respuestas guardados para referencia.

## 🔍 Validaciones Importantes

- **Marcas**: El `nombre_marca` debe ser único
- **Personas**: La `cedula` debe ser única
- **Vehículos**: Requiere que la `marca_id` exista
- **Propietarios**: No se puede asignar el mismo propietario dos veces al mismo vehículo

## 📝 Notas

- Todos los endpoints devuelven JSON
- Los códigos de estado HTTP siguen las convenciones REST:
  - `200`: OK
  - `201`: Created
  - `204`: No Content (para DELETE)
  - `400`: Bad Request (validación fallida)
  - `404`: Not Found
- La documentación completa está disponible en `/docs` (Swagger UI)

