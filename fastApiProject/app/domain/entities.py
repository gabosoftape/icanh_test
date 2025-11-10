from __future__ import annotations

from dataclasses import dataclass, field
from typing import List, Optional


@dataclass(slots=True)
class Marca:
    nombre_marca: str
    pais: str
    id: Optional[int] = None


@dataclass(slots=True)
class Persona:
    nombre: str
    cedula: str
    id: Optional[int] = None
    vehiculos_ids: List[int] = field(default_factory=list)


@dataclass(slots=True)
class Vehiculo:
    modelo: str
    marca_id: int
    numero_puertas: int
    color: str
    id: Optional[int] = None
    propietarios_ids: List[int] = field(default_factory=list)

