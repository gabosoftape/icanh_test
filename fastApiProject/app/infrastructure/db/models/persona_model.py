from __future__ import annotations

from sqlalchemy import Column, Integer, String
from sqlalchemy.orm import Mapped, relationship

from ..base import Base
from .associations import vehiculo_propietario


class PersonaDB(Base):
    """Modelo ORM para la entidad Persona."""

    __tablename__ = "personas"

    id: Mapped[int] = Column(Integer, primary_key=True, index=True)
    nombre: Mapped[str] = Column(String(120), nullable=False)
    cedula: Mapped[str] = Column(String(50), unique=True, nullable=False, index=True)

    vehiculos: Mapped[list["VehiculoDB"]] = relationship(
        "VehiculoDB",
        secondary=vehiculo_propietario,
        back_populates="propietarios",
    )

