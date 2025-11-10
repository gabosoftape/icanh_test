from __future__ import annotations

import os
from functools import lru_cache
from pathlib import Path

from dotenv import load_dotenv

# Cargar variables de entorno desde archivo .env si existe
env_path = Path(__file__).parent.parent.parent / ".env"
load_dotenv(dotenv_path=env_path)


class Settings:
    """Configuración de la aplicación cargada desde variables de entorno."""

    def __init__(self) -> None:
        # Credenciales de PostgreSQL
        self.postgres_host: str = os.getenv("POSTGRES_HOST", "localhost")
        self.postgres_port: int = int(os.getenv("POSTGRES_PORT", "5432"))
        self.postgres_user: str = os.getenv("POSTGRES_USER", "icanh")
        self.postgres_password: str = os.getenv("POSTGRES_PASSWORD", "123456")
        self.postgres_db: str = os.getenv("POSTGRES_DB", "icanh_vehiculos_db")
        
        # Construir URL de conexión usando psycopg2
        self.database_url: str = (
            f"postgresql+psycopg2://{self.postgres_user}:{self.postgres_password}"
            f"@{self.postgres_host}:{self.postgres_port}/{self.postgres_db}"
        )
        
        # Para pruebas, usar la misma configuración por defecto
        # o permitir override con variables específicas de test
        test_host = os.getenv("TEST_POSTGRES_HOST", self.postgres_host)
        test_port = int(os.getenv("TEST_POSTGRES_PORT", str(self.postgres_port)))
        test_user = os.getenv("TEST_POSTGRES_USER", self.postgres_user)
        test_password = os.getenv("TEST_POSTGRES_PASSWORD", self.postgres_password)
        test_db = os.getenv("TEST_POSTGRES_DB", self.postgres_db)
        
        self.test_database_url: str = (
            f"postgresql+psycopg2://{test_user}:{test_password}"
            f"@{test_host}:{test_port}/{test_db}"
        )


@lru_cache
def get_settings() -> Settings:
    return Settings()

