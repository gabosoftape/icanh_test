from __future__ import annotations

from typing import List, Optional

from pydantic import BaseModel, Field

try:  # pragma: no cover - compatibility with Pydantic v1/v2
    from pydantic import ConfigDict
except ImportError:  # pragma: no cover
    ConfigDict = None


class ORMBaseModel(BaseModel):
    if ConfigDict is not None:
        model_config = ConfigDict(from_attributes=True)
    else:  # pragma: no cover
        class Config:
            orm_mode = True


class MarcaBase(ORMBaseModel):
    nombre_marca: str = Field(..., min_length=1, max_length=100)
    pais: str = Field(..., min_length=1, max_length=100)


class MarcaCreate(MarcaBase):
    pass


class MarcaUpdate(ORMBaseModel):
    nombre_marca: Optional[str] = Field(None, min_length=1, max_length=100)
    pais: Optional[str] = Field(None, min_length=1, max_length=100)


class MarcaRead(MarcaBase):
    id: int


class PersonaBase(ORMBaseModel):
    nombre: str = Field(..., min_length=1, max_length=120)
    cedula: str = Field(..., min_length=5, max_length=50)


class PersonaCreate(PersonaBase):
    pass


class PersonaUpdate(ORMBaseModel):
    nombre: Optional[str] = Field(None, min_length=1, max_length=120)
    cedula: Optional[str] = Field(None, min_length=5, max_length=50)


class PersonaRead(PersonaBase):
    id: int


class VehiculoBase(ORMBaseModel):
    modelo: str = Field(..., min_length=1, max_length=120)
    marca_id: int
    numero_puertas: int = Field(..., ge=1, le=6)
    color: str = Field(..., min_length=1, max_length=80)


class VehiculoCreate(VehiculoBase):
    pass


class VehiculoUpdate(ORMBaseModel):
    modelo: Optional[str] = Field(None, min_length=1, max_length=120)
    marca_id: Optional[int] = None
    numero_puertas: Optional[int] = Field(None, ge=1, le=6)
    color: Optional[str] = Field(None, min_length=1, max_length=80)
    propietarios_ids: Optional[List[int]] = Field(default=None)


class VehiculoRead(VehiculoBase):
    id: int
    marca: MarcaRead
    propietarios: List[PersonaRead] = Field(default_factory=list)


class PersonaWithVehiculos(PersonaRead):
    vehiculos: List[VehiculoRead] = Field(default_factory=list)


class VehiculoWithPropietarios(VehiculoRead):
    pass


class PropietarioAsignacion(ORMBaseModel):
    persona_id: int

