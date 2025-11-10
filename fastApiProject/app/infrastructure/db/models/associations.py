from __future__ import annotations

from sqlalchemy import Column, ForeignKey, Integer, Table, UniqueConstraint

from ..base import Base

vehiculo_propietario = Table(
    "vehiculo_propietario",
    Base.metadata,
    Column("vehiculo_id", ForeignKey("vehiculos.id"), primary_key=True),
    Column("persona_id", ForeignKey("personas.id"), primary_key=True),
    UniqueConstraint("vehiculo_id", "persona_id", name="uq_vehiculo_persona"),
)

