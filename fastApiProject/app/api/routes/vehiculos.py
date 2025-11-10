from __future__ import annotations

from typing import List, Type

from fastapi import APIRouter, Depends, Response, status
from sqlalchemy.orm import Session

from app.application.services.vehiculo_service import VehiculoService
from app.domain.entities import Vehiculo as VehiculoEntity
from app.infrastructure.db.repositories import (
    SQLAlchemyMarcaRepository,
    SQLAlchemyPersonaRepository,
    SQLAlchemyVehiculoRepository,
)
from app.infrastructure.db.session import get_db
from app.schemas import (
    PropietarioAsignacion,
    VehiculoCreate,
    VehiculoRead,
    VehiculoUpdate,
)
from app.infrastructure.db.models import VehiculoDB

router = APIRouter(prefix="/api/vehiculos", tags=["Vehículos"])


def get_service(db: Session = Depends(get_db)) -> VehiculoService:
    veh_repo = SQLAlchemyVehiculoRepository(db)
    marca_repo = SQLAlchemyMarcaRepository(db)
    per_repo = SQLAlchemyPersonaRepository(db)
    return VehiculoService(veh_repo, marca_repo, per_repo)


@router.post("/", response_model=VehiculoRead, status_code=status.HTTP_201_CREATED)
def crear_vehiculo(
    vehiculo_in: VehiculoCreate, db: Session = Depends(get_db), service: VehiculoService = Depends(get_service)
) -> Type[VehiculoDB] | None:
    created = service.create(VehiculoEntity(**vehiculo_in.model_dump()))
    # devolver ORM para incluir marca y propietarios en respuesta
    return db.get(VehiculoDB, created.id)


@router.get("/", response_model=List[VehiculoRead])
def listar_vehiculos(
    skip: int = 0, limit: int = 100, db: Session = Depends(get_db), service: VehiculoService = Depends(get_service)
) -> list[Type[VehiculoDB]]:
    # por simplicidad, devolver ORM directamente para incluir relaciones
    return db.query(VehiculoDB).offset(skip).limit(limit).all()


@router.get("/{vehiculo_id}", response_model=VehiculoRead)
def obtener_vehiculo(
    vehiculo_id: int, db: Session = Depends(get_db), service: VehiculoService = Depends(get_service)
) -> Type[VehiculoDB] | None:
    service.get(vehiculo_id)
    return db.get(VehiculoDB, vehiculo_id)


@router.put("/{vehiculo_id}", response_model=VehiculoRead)
def actualizar_vehiculo(
    vehiculo_id: int, vehiculo_in: VehiculoUpdate, db: Session = Depends(get_db), service: VehiculoService = Depends(get_service)
) -> Type[VehiculoDB] | None:
    updated = service.update(vehiculo_id, vehiculo_in.model_dump(exclude_unset=True))
    return db.get(VehiculoDB, vehiculo_id)


@router.delete(
    "/{vehiculo_id}",
    status_code=status.HTTP_204_NO_CONTENT,
    response_class=Response,
)
def eliminar_vehiculo(
    vehiculo_id: int, service: VehiculoService = Depends(get_service)
) -> Response:
    service.delete(vehiculo_id)
    return Response(status_code=status.HTTP_204_NO_CONTENT)


@router.post(
    "/{vehiculo_id}/propietarios/",
    response_model=VehiculoRead,
    status_code=status.HTTP_201_CREATED,
)
def agregar_propietario_a_vehiculo(
    vehiculo_id: int,
    asignacion: PropietarioAsignacion,
    db: Session = Depends(get_db),
    service: VehiculoService = Depends(get_service),
) -> Type[VehiculoDB] | None:
    vehiculo = service.add_propietario(vehiculo_id, asignacion.persona_id)
    return db.get(VehiculoDB, vehiculo.id)

