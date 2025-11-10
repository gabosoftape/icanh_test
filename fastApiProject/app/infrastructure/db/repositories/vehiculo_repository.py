from __future__ import annotations

from typing import List, Optional

from sqlalchemy.orm import Session

from app.domain.entities import Vehiculo
from app.domain.repositories import VehiculoRepository

from ..models import PersonaDB, VehiculoDB


def _to_domain_vehiculo(model: VehiculoDB) -> Vehiculo:
    """Convierte un modelo ORM de Vehículo a una entidad de dominio."""
    return Vehiculo(
        id=model.id,
        modelo=model.modelo,
        marca_id=model.marca_id,
        numero_puertas=model.numero_puertas,
        color=model.color,
        propietarios_ids=[persona.id for persona in model.propietarios],
    )


class SQLAlchemyVehiculoRepository(VehiculoRepository):
    """Implementación del repositorio de Vehículo usando SQLAlchemy."""

    def __init__(self, session: Session) -> None:
        self._session = session

    def create(self, vehiculo: Vehiculo) -> Vehiculo:
        model = VehiculoDB(
            modelo=vehiculo.modelo,
            marca_id=vehiculo.marca_id,
            numero_puertas=vehiculo.numero_puertas,
            color=vehiculo.color,
        )
        self._session.add(model)
        self._session.commit()
        self._session.refresh(model)
        return _to_domain_vehiculo(model)

    def list(self, skip: int, limit: int) -> List[Vehiculo]:
        vehiculos = (
            self._session.query(VehiculoDB)
            .offset(skip)
            .limit(limit)
            .all()
        )
        return [_to_domain_vehiculo(vehiculo) for vehiculo in vehiculos]

    def get(self, vehiculo_id: int) -> Optional[Vehiculo]:
        vehiculo = self._session.get(VehiculoDB, vehiculo_id)
        return _to_domain_vehiculo(vehiculo) if vehiculo else None

    def update(self, vehiculo_id: int, data: dict) -> Vehiculo:
        vehiculo = self._get_model(vehiculo_id)
        for campo, valor in data.items():
            setattr(vehiculo, campo, valor)
        self._session.commit()
        self._session.refresh(vehiculo)
        return _to_domain_vehiculo(vehiculo)

    def delete(self, vehiculo_id: int) -> None:
        vehiculo = self._get_model(vehiculo_id)
        self._session.delete(vehiculo)
        self._session.commit()

    def add_propietario(self, vehiculo_id: int, persona_id: int) -> Vehiculo:
        vehiculo = self._get_model(vehiculo_id)
        persona = self._session.get(PersonaDB, persona_id)
        if not persona:
            raise ValueError("Persona no encontrada.")
        if persona in vehiculo.propietarios:
            raise ValueError("El propietario ya está asociado al vehículo.")
        vehiculo.propietarios.append(persona)
        self._session.commit()
        self._session.refresh(vehiculo)
        return _to_domain_vehiculo(vehiculo)

    def set_propietarios(self, vehiculo_id: int, propietarios_ids: List[int]) -> Vehiculo:
        vehiculo = self._get_model(vehiculo_id)
        propietarios = (
            self._session.query(PersonaDB)
            .filter(PersonaDB.id.in_(propietarios_ids))
            .all()
        )
        if len(propietarios) != len(propietarios_ids):
            raise ValueError("Alguno de los propietarios no existe.")
        vehiculo.propietarios = propietarios
        self._session.commit()
        self._session.refresh(vehiculo)
        return _to_domain_vehiculo(vehiculo)

    def _get_model(self, vehiculo_id: int) -> VehiculoDB:
        """Obtiene un modelo VehiculoDB o lanza una excepción si no existe."""
        vehiculo = self._session.get(VehiculoDB, vehiculo_id)
        if not vehiculo:
            raise ValueError("Vehículo no encontrado.")
        return vehiculo

