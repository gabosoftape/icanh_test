# 📬 Guía de Uso - Colección de Postman para Odoo ICANH

Esta guía explica cómo usar la colección de Postman para probar todos los endpoints de la API REST del módulo Odoo ICANH.

## 📥 Importar la Colección

1. Abre Postman
2. Haz clic en **Import** (arriba a la izquierda)
3. Selecciona el archivo `postman_collection.json` de la carpeta `odoo_icanh_mod`
4. La colección se importará con todas las carpetas organizadas

## 🔧 Configurar Variables

La colección incluye variables que se actualizan automáticamente:

### Variables de Colección

- **`base_url`**: URL base de Odoo (default: `http://localhost:8069`)
  - Si tu Odoo corre en otro puerto, cambia este valor
  - Ejemplo: `http://localhost:8069` o `http://tu-servidor:8069`

- **`marca_id`**: ID de una marca (se actualiza automáticamente después de crear una)
- **`persona_id`**: ID de una persona (se actualiza automáticamente después de crear una)
- **`vehiculo_id`**: ID de un vehículo (se actualiza automáticamente después de crear uno)

### Para cambiar la URL base:

1. Haz clic derecho en la colección "Odoo ICANH API - Gestión de Vehículos"
2. Selecciona **Edit**
3. Ve a la pestaña **Variables**
4. Modifica el valor de `base_url` si tu Odoo corre en otro puerto
5. Haz clic en **Save**

## 📋 Estructura de la Colección

### 🏷️ Marcas
- **Listar todas las marcas**: `GET /api/icanh/marcas`
- **Obtener marca por ID**: `GET /api/icanh/marcas/{id}`
- **Crear nueva marca**: `POST /api/icanh/marcas`
- **Actualizar marca**: `PUT /api/icanh/marcas/{id}`
- **Eliminar marca**: `DELETE /api/icanh/marcas/{id}`

### 👤 Personas
- **Listar todas las personas**: `GET /api/icanh/personas`
- **Obtener persona por ID**: `GET /api/icanh/personas/{id}`
- **Crear nueva persona**: `POST /api/icanh/personas`
- **Actualizar persona**: `PUT /api/icanh/personas/{id}`
- **Eliminar persona**: `DELETE /api/icanh/personas/{id}`
- **Obtener vehículos de una persona**: `GET /api/icanh/personas/{id}/vehiculos`

### 🚗 Vehículos
- **Listar todos los vehículos**: `GET /api/icanh/vehiculos`
- **Obtener vehículo por ID**: `GET /api/icanh/vehiculos/{id}`
- **Crear nuevo vehículo**: `POST /api/icanh/vehiculos`
- **Actualizar vehículo**: `PUT /api/icanh/vehiculos/{id}`
- **Eliminar vehículo**: `DELETE /api/icanh/vehiculos/{id}`
- **Agregar propietario a vehículo**: `POST /api/icanh/vehiculos/{id}/propietarios`

## 🚀 Flujo de Prueba Recomendado

### 1. Crear una Marca
1. Ve a **Marcas** → **Crear nueva marca**
2. Modifica el body si lo deseas:
   ```json
   {
       "nombre_marca": "Toyota",
       "pais": "Japón"
   }
   ```
3. Haz clic en **Send**
4. El `marca_id` se guardará automáticamente en las variables

### 2. Crear una Persona
1. Ve a **Personas** → **Crear nueva persona**
2. Modifica el body si lo deseas:
   ```json
   {
       "nombre": "Juan Pérez",
       "cedula": "1234567890"
   }
   ```
3. Haz clic en **Send**
4. El `persona_id` se guardará automáticamente en las variables

### 3. Crear un Vehículo
1. Ve a **Vehículos** → **Crear nuevo vehículo**
2. El body ya incluye las variables `{{marca_id}}` y `{{persona_id}}`:
   ```json
   {
       "modelo": "Corolla",
       "marca_id": {{marca_id}},
       "numero_puertas": 4,
       "color": "Rojo",
       "propietarios_ids": [{{persona_id}}]
   }
   ```
3. Haz clic en **Send**
4. El `vehiculo_id` se guardará automáticamente en las variables

### 4. Probar Relaciones
1. **Obtener vehículos de una persona**: 
   - Ve a **Personas** → **Obtener vehículos de una persona**
   - Usa el `{{persona_id}}` guardado automáticamente
   - Haz clic en **Send**

2. **Agregar propietario a vehículo**:
   - Ve a **Vehículos** → **Agregar propietario a vehículo"
   - Usa el `{{vehiculo_id}}` y `{{persona_id}}` guardados
   - Haz clic en **Send**

## 🔄 Actualización Automática de Variables

Los endpoints de creación (POST) incluyen scripts de prueba que:
- Capturan el ID de la entidad creada desde `response.data.id` (formato Odoo)
- Lo guardan automáticamente en las variables de la colección
- Lo muestran en la consola de Postman

**Nota importante**: La respuesta de Odoo tiene el formato:
```json
{
    "status": "success",
    "message": "Marca creada exitosamente",
    "data": {
        "id": 1,
        "nombre_marca": "Toyota",
        "pais": "Japón"
    }
}
```

Los scripts extraen el ID desde `jsonData.data.id`.

## 📝 Ejemplos de Body

### Crear Marca
```json
{
    "nombre_marca": "Toyota",
    "pais": "Japón"
}
```

### Crear Persona
```json
{
    "nombre": "Juan Pérez",
    "cedula": "1234567890"
}
```

### Crear Vehículo
```json
{
    "modelo": "Corolla",
    "marca_id": 1,
    "numero_puertas": 4,
    "color": "Rojo",
    "propietarios_ids": [1]
}
```

### Agregar Propietario
```json
{
    "persona_id": 1
}
```

## 📊 Formato de Respuestas

### Respuesta exitosa (200/201)
```json
{
    "status": "success",
    "message": "Marca creada exitosamente",
    "data": {
        "id": 1,
        "nombre_marca": "Toyota",
        "pais": "Japón"
    }
}
```

### Respuesta de error (400/404/500)
```json
{
    "status": "error",
    "message": "Marca no encontrada",
    "data": null
}
```

## 🧪 Tests Automáticos

Algunos endpoints incluyen tests automáticos que:
- Verifican el código de respuesta
- Guardan IDs en variables para uso posterior (desde `data.id`)
- Muestran información en la consola

Puedes ver los resultados en la pestaña **Test Results** después de enviar un request.

## ⚠️ Notas Importantes

1. **Orden de creación**: 
   - Primero crea una **Marca**
   - Luego crea una **Persona**
   - Finalmente crea un **Vehículo** (requiere marca_id)

2. **Variables**: 
   - Las variables se actualizan automáticamente después de crear entidades
   - Si necesitas usar IDs diferentes, puedes editarlos manualmente en las variables de la colección

3. **URL Base**: 
   - Por defecto está configurada para `http://localhost:8069` (puerto estándar de Odoo)
   - Si usas otro puerto o servidor, cambia la variable `base_url`

4. **Sin Autenticación**: 
   - Todas las rutas de la API son públicas (`auth='none'`)
   - No necesitas configurar tokens ni credenciales

5. **Formato de Respuesta**: 
   - Odoo devuelve respuestas en formato `{status, message, data}`
   - Los IDs están en `data.id`, no directamente en la raíz

## 🆘 Solución de Problemas

### Error: "Connection refused"
- Verifica que Odoo esté corriendo
- Asegúrate de que la URL base sea correcta
- Verifica que el módulo esté instalado en Odoo

### Error: "404 Not Found"
- Verifica que la ruta sea correcta (debe incluir `/api/icanh`)
- Asegúrate de que el módulo esté instalado y activo
- Verifica que el servidor Odoo esté corriendo en el puerto correcto

### Error: "500 Internal Server Error"
- Revisa los logs de Odoo
- Verifica que la base de datos esté configurada correctamente
- Asegúrate de que todas las dependencias del módulo estén instaladas

### Error: "422 Unprocessable Entity"
- Revisa el formato JSON del body
- Verifica que todos los campos requeridos estén presentes
- Revisa las validaciones en los modelos de Odoo

### Variables no se actualizan
- Verifica que los tests automáticos estén habilitados
- Revisa la consola de Postman para ver si hay errores en los scripts
- Asegúrate de que la respuesta tenga el formato esperado (`data.id`)

## 🔍 Verificar que el Módulo está Instalado

1. Accede a Odoo: `http://localhost:8069`
2. Ve a **Aplicaciones**
3. Busca "Gestión de Vehículos ICANH"
4. Verifica que esté instalado y activo

## 📚 Recursos Adicionales

- **Documentación del Módulo**: Ver `README.md` en el módulo
- **Logs de Odoo**: Revisa los logs del servidor para más detalles de errores
- **API desde Navegador**: Puedes probar los endpoints GET directamente en el navegador

## 🎯 Ejemplo Completo de Flujo

```bash
# 1. Crear marca
POST http://localhost:8069/api/icanh/marcas
{
    "nombre_marca": "Toyota",
    "pais": "Japón"
}
# Respuesta: {"status": "success", "data": {"id": 1, ...}}

# 2. Crear persona
POST http://localhost:8069/api/icanh/personas
{
    "nombre": "Juan Pérez",
    "cedula": "1234567890"
}
# Respuesta: {"status": "success", "data": {"id": 1, ...}}

# 3. Crear vehículo
POST http://localhost:8069/api/icanh/vehiculos
{
    "modelo": "Corolla",
    "marca_id": 1,
    "numero_puertas": 4,
    "color": "Rojo",
    "propietarios_ids": [1]
}
# Respuesta: {"status": "success", "data": {"id": 1, ...}}

# 4. Obtener vehículos de una persona
GET http://localhost:8069/api/icanh/personas/1/vehiculos
```

