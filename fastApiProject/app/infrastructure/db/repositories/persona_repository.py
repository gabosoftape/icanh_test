from __future__ import annotations

from typing import List, Optional

from sqlalchemy.exc import IntegrityError
from sqlalchemy.orm import Session

from app.domain.entities import Persona
from app.domain.repositories import PersonaRepository

from ..models import PersonaDB


def _to_domain_persona(model: PersonaDB) -> Persona:
    """Convierte un modelo ORM de Persona a una entidad de dominio."""
    return Persona(
        id=model.id,
        nombre=model.nombre,
        cedula=model.cedula,
        vehiculos_ids=[vehiculo.id for vehiculo in model.vehiculos],
    )


class SQLAlchemyPersonaRepository(PersonaRepository):
    """Implementación del repositorio de Persona usando SQLAlchemy."""

    def __init__(self, session: Session) -> None:
        self._session = session

    def create(self, persona: Persona) -> Persona:
        model = PersonaDB(nombre=persona.nombre, cedula=persona.cedula)
        self._session.add(model)
        try:
            self._session.commit()
        except IntegrityError as exc:
            self._session.rollback()
            raise ValueError("Ya existe una persona con esa cédula.") from exc
        self._session.refresh(model)
        return _to_domain_persona(model)

    def list(self, skip: int, limit: int) -> List[Persona]:
        personas = (
            self._session.query(PersonaDB)
            .offset(skip)
            .limit(limit)
            .all()
        )
        return [_to_domain_persona(persona) for persona in personas]

    def get(self, persona_id: int) -> Optional[Persona]:
        persona = self._session.get(PersonaDB, persona_id)
        return _to_domain_persona(persona) if persona else None

    def update(self, persona_id: int, data: dict) -> Persona:
        persona = self._session.get(PersonaDB, persona_id)
        if not persona:
            raise ValueError("Persona no encontrada.")
        for campo, valor in data.items():
            setattr(persona, campo, valor)
        try:
            self._session.commit()
        except IntegrityError as exc:
            self._session.rollback()
            raise ValueError("Ya existe una persona con esa cédula.") from exc
        self._session.refresh(persona)
        return _to_domain_persona(persona)

    def delete(self, persona_id: int) -> None:
        persona = self._session.get(PersonaDB, persona_id)
        if not persona:
            raise ValueError("Persona no encontrada.")
        self._session.delete(persona)
        self._session.commit()

