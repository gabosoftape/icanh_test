from __future__ import annotations

from .marca_repository import SQLAlchemyMarcaRepository
from .persona_repository import SQLAlchemyPersonaRepository
from .vehiculo_repository import SQLAlchemyVehiculoRepository

__all__ = [
    "SQLAlchemyMarcaRepository",
    "SQLAlchemyPersonaRepository",
    "SQLAlchemyVehiculoRepository",
]

