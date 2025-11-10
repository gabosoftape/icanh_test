from __future__ import annotations

from .associations import vehiculo_propietario
from .marca_model import MarcaVehiculoDB
from .persona_model import PersonaDB
from .vehiculo_model import VehiculoDB

__all__ = [
    "MarcaVehiculoDB",
    "PersonaDB",
    "VehiculoDB",
    "vehiculo_propietario",
]

