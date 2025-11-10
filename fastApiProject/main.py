import logging
from fastapi import FastAPI

from app.core.config import get_settings
from app.infrastructure.db.base import Base
from app.infrastructure.db.session import engine
from app.api.routes import marcas, personas, vehiculos

# Configurar logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

API_TITLE = "Gestión de Vehículos API"
API_DESCRIPTION = (
    "API RESTful para administrar marcas de vehículos, personas y su relación de "
    "propiedad con vehículos. Incluye operaciones CRUD completas y endpoints de "
    "relaciones."
)
API_VERSION = "1.0.0"

tags_metadata = [
    {
        "name": "Marcas",
        "description": "Operaciones CRUD para gestionar marcas de vehículos.",
    },
    {
        "name": "Personas",
        "description": "Operaciones CRUD para personas y consulta de vehículos asociados.",
    },
    {
        "name": "Vehículos",
        "description": "Operaciones CRUD para vehículos y asignación de propietarios.",
    },
]

app = FastAPI(
    title=API_TITLE,
    description=API_DESCRIPTION,
    version=API_VERSION,
    openapi_tags=tags_metadata,
)


@app.on_event("startup")
def on_startup() -> None:
    """Inicializa la base de datos al arrancar la aplicación."""
    settings = get_settings()
    
    # Mostrar información de conexión (sin contraseña completa)
    masked_password = "*" * len(settings.postgres_password) if settings.postgres_password else "***"
    logger.info(
        f"Intentando conectar a PostgreSQL: "
        f"{settings.postgres_user}:***@{settings.postgres_host}:{settings.postgres_port}/{settings.postgres_db}"
    )
    
    try:
        # Intentar crear las tablas
        Base.metadata.create_all(bind=engine)
        logger.info("✅ Tablas creadas/verificadas exitosamente")
    except Exception as e:
        error_msg = str(e)
        error_type = type(e).__name__
        
        # Extraer información más específica del error
        detailed_error = ""
        if "password authentication failed" in error_msg.lower():
            detailed_error = "❌ Autenticación fallida: Usuario o contraseña incorrectos"
        elif "could not connect to server" in error_msg.lower():
            detailed_error = "❌ No se puede conectar al servidor: Verifica que PostgreSQL esté corriendo"
        elif "database" in error_msg.lower() and "does not exist" in error_msg.lower():
            detailed_error = "❌ La base de datos no existe"
        elif "connection refused" in error_msg.lower():
            detailed_error = "❌ Conexión rechazada: Verifica el puerto y que PostgreSQL esté corriendo"
        else:
            detailed_error = f"❌ Error de tipo: {error_type}"
        
        logger.error(f"{detailed_error}")
        logger.error(f"Error completo: {error_msg}")
        logger.error(
            f"\n💡 Configuración actual:\n"
            f"   Host: {settings.postgres_host}\n"
            f"   Puerto: {settings.postgres_port}\n"
            f"   Usuario: {settings.postgres_user}\n"
            f"   Base de datos: {settings.postgres_db}\n"
            f"\n💡 Pasos para resolver:\n"
            f"   1. Verifica las credenciales en tu archivo .env\n"
            f"   2. Prueba conectarte manualmente:\n"
            f"      psql -h {settings.postgres_host} -p {settings.postgres_port} -U {settings.postgres_user} -d {settings.postgres_db}\n"
            f"   3. Si el usuario no existe, créalo:\n"
            f"      CREATE USER {settings.postgres_user} WITH PASSWORD '{settings.postgres_password}';\n"
            f"   4. Otorga permisos:\n"
            f"      GRANT ALL PRIVILEGES ON DATABASE {settings.postgres_db} TO {settings.postgres_user};\n"
            f"   5. Verifica el archivo pg_hba.conf si usas autenticación local\n"
            f"\n💡 La aplicación continuará, pero las operaciones de BD fallarán hasta que se resuelva."
        )
        # No lanzar la excepción para que la app pueda iniciar
        # Las tablas se crearán cuando se haga la primera conexión exitosa


@app.get("/", tags=["Marcas"])
async def root():
    return {"message": "Bienvenido a la API de gestión de vehículos"}


@app.get("/health", tags=["Sistema"])
async def health_check():
    """Endpoint para verificar el estado de la aplicación y la conexión a la base de datos."""
    from sqlalchemy import text
    from app.infrastructure.db.session import SessionLocal
    
    settings = get_settings()
    status = {
        "status": "ok",
        "database": {
            "host": settings.postgres_host,
            "port": settings.postgres_port,
            "database": settings.postgres_db,
            "user": settings.postgres_user,
            "connected": False,
        }
    }
    
    try:
        db = SessionLocal()
        try:
            db.execute(text("SELECT 1"))
            status["database"]["connected"] = True
            status["message"] = "Aplicación y base de datos funcionando correctamente"
        finally:
            db.close()
    except Exception as e:
        status["status"] = "error"
        status["database"]["connected"] = False
        status["error"] = str(e)
        status["message"] = "Error al conectar con la base de datos"
    
    return status


app.include_router(marcas.router)
app.include_router(personas.router)
app.include_router(vehiculos.router)
