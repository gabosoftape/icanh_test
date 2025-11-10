from __future__ import annotations

from sqlalchemy import Column, ForeignKey, Integer, String
from sqlalchemy.orm import Mapped, relationship

from ..base import Base
from .associations import vehiculo_propietario


class VehiculoDB(Base):
    """Modelo ORM para la entidad Vehículo."""

    __tablename__ = "vehiculos"

    id: Mapped[int] = Column(Integer, primary_key=True, index=True)
    modelo: Mapped[str] = Column(String(120), nullable=False)
    marca_id: Mapped[int] = Column(Integer, ForeignKey("marcas.id"), nullable=False)
    numero_puertas: Mapped[int] = Column(Integer, nullable=False)
    color: Mapped[str] = Column(String(80), nullable=False)

    marca: Mapped["MarcaVehiculoDB"] = relationship("MarcaVehiculoDB", back_populates="vehiculos")
    propietarios: Mapped[list["PersonaDB"]] = relationship(
        "PersonaDB",
        secondary=vehiculo_propietario,
        back_populates="vehiculos",
    )

