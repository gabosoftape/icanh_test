# 📬 Guía de Uso - Colección de Postman

Esta guía explica cómo usar la colección de Postman para probar todos los endpoints de la API de Gestión de Vehículos.

## 📥 Importar la Colección

1. Abre Postman
2. Haz clic en **Import** (arriba a la izquierda)
3. Selecciona el archivo `postman_collection.json`
4. La colección se importará con todas las carpetas organizadas

## 🔧 Configurar Variables

La colección incluye variables que se actualizan automáticamente:

### Variables de Colección

- **`base_url`**: URL base de la API (default: `http://localhost:9000`)
  - Si tu API corre en otro puerto, cambia este valor
  - Ejemplo: `http://localhost:8000`

- **`marca_id`**: ID de una marca (se actualiza automáticamente después de crear una)
- **`persona_id`**: ID de una persona (se actualiza automáticamente después de crear una)
- **`vehiculo_id`**: ID de un vehículo (se actualiza automáticamente después de crear uno)

### Para cambiar la URL base:

1. Haz clic derecho en la colección "API Gestión de Vehículos"
2. Selecciona **Edit**
3. Ve a la pestaña **Variables**
4. Modifica el valor de `base_url` si tu API corre en otro puerto
5. Haz clic en **Save**

## 📋 Estructura de la Colección

### 🏷️ Marcas
- **Listar todas las marcas**: `GET /api/marcas`
- **Obtener marca por ID**: `GET /api/marcas/{id}`
- **Crear nueva marca**: `POST /api/marcas`
- **Actualizar marca**: `PUT /api/marcas/{id}`
- **Eliminar marca**: `DELETE /api/marcas/{id}`

### 👤 Personas
- **Listar todas las personas**: `GET /api/personas`
- **Obtener persona por ID**: `GET /api/personas/{id}`
- **Crear nueva persona**: `POST /api/personas`
- **Actualizar persona**: `PUT /api/personas/{id}`
- **Eliminar persona**: `DELETE /api/personas/{id}`
- **Obtener vehículos de una persona**: `GET /api/personas/{id}/vehiculos`

### 🚗 Vehículos
- **Listar todos los vehículos**: `GET /api/vehiculos`
- **Obtener vehículo por ID**: `GET /api/vehiculos/{id}`
- **Crear nuevo vehículo**: `POST /api/vehiculos`
- **Actualizar vehículo**: `PUT /api/vehiculos/{id}`
- **Eliminar vehículo**: `DELETE /api/vehiculos/{id}`
- **Agregar propietario a vehículo**: `POST /api/vehiculos/{id}/propietarios`

### 📚 Documentación
- **Swagger UI**: `GET /api/documentation`
- **Swagger JSON**: `GET /docs/api-docs.json`

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
   - Ve a **Vehículos** → **Agregar propietario a vehículo**
   - Usa el `{{vehiculo_id}}` y `{{persona_id}}` guardados
   - Haz clic en **Send**

## 🔄 Actualización Automática de Variables

Los endpoints de creación (POST) incluyen scripts de prueba que:
- Capturan el ID de la entidad creada
- Lo guardan automáticamente en las variables de la colección
- Lo muestran en la consola de Postman

Esto permite usar esos IDs en requests posteriores sin copiarlos manualmente.

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

## 🧪 Tests Automáticos

Algunos endpoints incluyen tests automáticos que:
- Verifican el código de respuesta
- Guardan IDs en variables para uso posterior
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
   - Por defecto está configurada para `http://localhost:9000`
   - Si usas otro puerto, cambia la variable `base_url`

4. **Sin Autenticación**: 
   - Todas las rutas de la API son públicas
   - No necesitas configurar tokens ni credenciales

## 🆘 Solución de Problemas

### Error: "Connection refused"
- Verifica que el servidor Laravel esté corriendo
- Asegúrate de que la URL base sea correcta

### Error: "404 Not Found"
- Verifica que la ruta sea correcta (debe incluir `/api`)
- Asegúrate de que el servidor esté corriendo en el puerto correcto

### Variables no se actualizan
- Verifica que los tests automáticos estén habilitados
- Revisa la consola de Postman para ver si hay errores en los scripts

### Error: "422 Unprocessable Entity"
- Revisa el formato JSON del body
- Verifica que todos los campos requeridos estén presentes
- Revisa las validaciones en los controladores

## 📚 Recursos Adicionales

- **Documentación Swagger**: `http://localhost:9000/api/documentation`
- **JSON de Swagger**: `http://localhost:9000/docs/api-docs.json`
- **Guía de Acceso a la API**: Ver `API_ACCESS.md`

