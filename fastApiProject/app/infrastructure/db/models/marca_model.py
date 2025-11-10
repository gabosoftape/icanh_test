from __future__ import annotations

from sqlalchemy import Column, Integer, String
from sqlalchemy.orm import Mapped, relationship

from ..base import Base


class MarcaVehiculoDB(Base):
    """Modelo ORM para la entidad Marca de Vehículo."""

    __tablename__ = "marcas"

    id: Mapped[int] = Column(Integer, primary_key=True, index=True)
    nombre_marca: Mapped[str] = Column(String(100), unique=True, nullable=False, index=True)
    pais: Mapped[str] = Column(String(100), nullable=False)

    vehiculos: Mapped[list["VehiculoDB"]] = relationship(
        "VehiculoDB", back_populates="marca", cascade="all, delete-orphan"
    )

