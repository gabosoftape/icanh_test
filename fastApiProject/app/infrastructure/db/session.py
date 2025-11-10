from __future__ import annotations

import logging
from contextlib import contextmanager
from typing import Dict, Generator, Iterator

from sqlalchemy import create_engine
from sqlalchemy.exc import OperationalError
from sqlalchemy.orm import Session, sessionmaker

from app.core.config import get_settings

logger = logging.getLogger(__name__)
settings = get_settings()


def _build_engine(database_url: str):
    """Construye el motor de SQLAlchemy con configuración apropiada usando psycopg2."""
    connect_args: Dict[str, object] = {}
    if database_url.startswith("sqlite"):
        connect_args = {"check_same_thread": False}
    
    # Para PostgreSQL, asegurar que el driver psycopg2 esté especificado
    if database_url.startswith("postgresql://") and "+psycopg2" not in database_url:
        database_url = database_url.replace("postgresql://", "postgresql+psycopg2://")
    elif database_url.startswith("postgresql+psycopg2://"):
        # Ya está correctamente configurado con psycopg2
        pass
    
    return create_engine(
        database_url,
        connect_args=connect_args,
        future=True,
        echo=False,
        pool_pre_ping=True,
        pool_size=5,
        max_overflow=10,
    )


engine = _build_engine(settings.database_url)
SessionLocal = sessionmaker(bind=engine, autocommit=False, autoflush=False, future=True)


def get_db() -> Generator[Session, None, None]:
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()


@contextmanager
def override_db(database_url: str) -> Iterator[Session]:
    temporary_engine = _build_engine(database_url)
    TestingSessionLocal = sessionmaker(
        bind=temporary_engine, autocommit=False, autoflush=False, future=True
    )
    db = TestingSessionLocal()
    try:
        yield db
    finally:
        db.close()
        temporary_engine.dispose()

