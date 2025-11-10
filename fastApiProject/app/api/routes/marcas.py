from __future__ import annotations

from typing import List

from fastapi import APIRouter, Depends, Response, status
from sqlalchemy.orm import Session

from app.application.services.marca_service import MarcaService
from app.domain.entities import Marca as MarcaEntity
from app.infrastructure.db.repositories import SQLAlchemyMarcaRepository
from app.infrastructure.db.session import get_db
from app.schemas import MarcaCreate, MarcaRead, MarcaUpdate

router = APIRouter(prefix="/api/marcas", tags=["Marcas"])


def get_service(db: Session = Depends(get_db)) -> MarcaService:
    return MarcaService(SQLAlchemyMarcaRepository(db))


@router.post("/", response_model=MarcaRead, status_code=status.HTTP_201_CREATED)
def crear_marca(marca_in: MarcaCreate, service: MarcaService = Depends(get_service)) -> MarcaRead:
    created = service.create(MarcaEntity(**marca_in.model_dump()))
    return MarcaRead.model_validate(created)


@router.get("/", response_model=List[MarcaRead])
def listar_marcas(
    skip: int = 0, limit: int = 100, service: MarcaService = Depends(get_service)
) -> List[MarcaRead]:
    return [MarcaRead.model_validate(m) for m in service.list(skip, limit)]


@router.get("/{marca_id}", response_model=MarcaRead)
def obtener_marca(marca_id: int, service: MarcaService = Depends(get_service)) -> MarcaRead:
    marca = service.get(marca_id)
    return MarcaRead.model_validate(marca)


@router.put("/{marca_id}", response_model=MarcaRead)
def actualizar_marca(
    marca_id: int, marca_in: MarcaUpdate, service: MarcaService = Depends(get_service)
) -> MarcaRead:
    updated = service.update(marca_id, marca_in.model_dump(exclude_unset=True))
    return MarcaRead.model_validate(updated)


@router.delete(
    "/{marca_id}",
    status_code=status.HTTP_204_NO_CONTENT,
    response_class=Response,
)
def eliminar_marca(marca_id: int, service: MarcaService = Depends(get_service)) -> Response:
    service.delete(marca_id)
    return Response(status_code=status.HTTP_204_NO_CONTENT)

