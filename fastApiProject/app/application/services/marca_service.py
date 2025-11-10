from __future__ import annotations

from typing import List

from fastapi import HTTPException, status

from app.domain.entities import Marca
from app.domain.repositories import MarcaRepository


class MarcaService:
    def __init__(self, repository: MarcaRepository) -> None:
        self._repository = repository

    def create(self, marca: Marca) -> Marca:
        try:
            return self._repository.create(marca)
        except ValueError as exc:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=str(exc),
            ) from exc

    def list(self, skip: int, limit: int) -> List[Marca]:
        return self._repository.list(skip, limit)

    def get(self, marca_id: int) -> Marca:
        marca = self._repository.get(marca_id)
        if not marca:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND, detail="Marca no encontrada."
            )
        return marca

    def update(self, marca_id: int, data: dict) -> Marca:
        self.get(marca_id)
        try:
            return self._repository.update(marca_id, data)
        except ValueError as exc:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=str(exc),
            ) from exc

    def delete(self, marca_id: int) -> None:
        self.get(marca_id)
        self._repository.delete(marca_id)

