from __future__ import annotations

from typing import List

from fastapi import APIRouter, Depends, Response, status
from sqlalchemy.orm import Session

from app.application.services.persona_service import PersonaService
from app.domain.entities import Persona as PersonaEntity
from app.infrastructure.db.repositories import SQLAlchemyPersonaRepository
from app.infrastructure.db.session import get_db
from app.schemas import PersonaCreate, PersonaRead, PersonaUpdate, VehiculoRead
from app.infrastructure.db.models import PersonaDB

router = APIRouter(prefix="/api/personas", tags=["Personas"])


def get_service(db: Session = Depends(get_db)) -> PersonaService:
    return PersonaService(SQLAlchemyPersonaRepository(db))


@router.post("/", response_model=PersonaRead, status_code=status.HTTP_201_CREATED)
def crear_persona(persona_in: PersonaCreate, service: PersonaService = Depends(get_service)) -> PersonaRead:
    created = service.create(PersonaEntity(**persona_in.model_dump()))
    return PersonaRead.model_validate(created)


@router.get("/", response_model=List[PersonaRead])
def listar_personas(
    skip: int = 0, limit: int = 100, service: PersonaService = Depends(get_service)
) -> List[PersonaRead]:
    return [PersonaRead.model_validate(p) for p in service.list(skip, limit)]


@router.get("/{persona_id}", response_model=PersonaRead)
def obtener_persona(persona_id: int, service: PersonaService = Depends(get_service)) -> PersonaRead:
    persona = service.get(persona_id)
    return PersonaRead.model_validate(persona)


@router.put("/{persona_id}", response_model=PersonaRead)
def actualizar_persona(
    persona_id: int, persona_in: PersonaUpdate, service: PersonaService = Depends(get_service)
) -> PersonaRead:
    updated = service.update(persona_id, persona_in.model_dump(exclude_unset=True))
    return PersonaRead.model_validate(updated)


@router.delete(
    "/{persona_id}",
    status_code=status.HTTP_204_NO_CONTENT,
    response_class=Response,
)
def eliminar_persona(persona_id: int, service: PersonaService = Depends(get_service)) -> Response:
    service.delete(persona_id)
    return Response(status_code=status.HTTP_204_NO_CONTENT)


@router.get("/{persona_id}/vehiculos/", response_model=List[VehiculoRead])
def listar_vehiculos_por_persona(
    persona_id: int, db: Session = Depends(get_db), service: PersonaService = Depends(get_service)
):
    service.get(persona_id)  # valida existencia
    orm_persona = db.get(PersonaDB, persona_id)
    return orm_persona.vehiculos

