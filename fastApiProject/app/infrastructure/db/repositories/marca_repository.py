from __future__ import annotations

from typing import List, Optional

from sqlalchemy.exc import IntegrityError
from sqlalchemy.orm import Session

from app.domain.entities import Marca
from app.domain.repositories import MarcaRepository

from ..models import MarcaVehiculoDB


def _to_domain_marca(model: MarcaVehiculoDB) -> Marca:
    """Convierte un modelo ORM de Marca a una entidad de dominio."""
    return Marca(
        id=model.id,
        nombre_marca=model.nombre_marca,
        pais=model.pais,
    )


class SQLAlchemyMarcaRepository(MarcaRepository):
    """Implementación del repositorio de Marca usando SQLAlchemy."""

    def __init__(self, session: Session) -> None:
        self._session = session

    """ Función crear """
    def create(self, marca: Marca) -> Marca:
        model = MarcaVehiculoDB(nombre_marca=marca.nombre_marca, pais=marca.pais)
        self._session.add(model)
        try:
            self._session.commit()
        except IntegrityError as exc:
            self._session.rollback()
            raise ValueError("Ya existe una marca con ese nombre.") from exc
        self._session.refresh(model)
        return _to_domain_marca(model)

    """ Función listar MarcaRepository """
    def list(self, skip: int, limit: int) -> List[Marca]:
        marcas = (
            self._session.query(MarcaVehiculoDB)
            .offset(skip)
            .limit(limit)
            .all()
        )
        return [_to_domain_marca(marca) for marca in marcas]

    """ Función obtener por id """
    def get(self, marca_id: int) -> Optional[Marca]:
        marca = self._session.get(MarcaVehiculoDB, marca_id)
        return _to_domain_marca(marca) if marca else None

    """ Función actualizar"""
    def update(self, marca_id: int, data: dict) -> Marca:
        marca = self._session.get(MarcaVehiculoDB, marca_id)
        if not marca:
            raise ValueError("Marca no encontrada.")
        for campo, valor in data.items():
            setattr(marca, campo, valor)
        try:
            self._session.commit()
        except IntegrityError as exc:
            self._session.rollback()
            raise ValueError("Ya existe una marca con ese nombre.") from exc
        self._session.refresh(marca)
        return _to_domain_marca(marca)

    """Metodo Borrar"""
    def delete(self, marca_id: int) -> None:
        marca = self._session.get(MarcaVehiculoDB, marca_id)
        if not marca:
            raise ValueError("Marca no encontrada.")
        self._session.delete(marca)
        self._session.commit()

