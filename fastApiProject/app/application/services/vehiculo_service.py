from __future__ import annotations

from typing import List

from fastapi import HTTPException, status

from app.domain.entities import Vehiculo
from app.domain.repositories import MarcaRepository, PersonaRepository, VehiculoRepository


class VehiculoService:
    def __init__(
        self,
        vehiculo_repository: VehiculoRepository,
        marca_repository: MarcaRepository,
        persona_repository: PersonaRepository,
    ) -> None:
        self._vehiculo_repository = vehiculo_repository
        self._marca_repository = marca_repository
        self._persona_repository = persona_repository

    def create(self, vehiculo: Vehiculo) -> Vehiculo:
        self._ensure_marca_exists(vehiculo.marca_id)
        return self._vehiculo_repository.create(vehiculo)

    def list(self, skip: int, limit: int) -> List[Vehiculo]:
        return self._vehiculo_repository.list(skip, limit)

    def get(self, vehiculo_id: int) -> Vehiculo:
        vehiculo = self._vehiculo_repository.get(vehiculo_id)
        if not vehiculo:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND,
                detail="Vehículo no encontrado.",
            )
        return vehiculo

    def update(self, vehiculo_id: int, data: dict) -> Vehiculo:
        if "marca_id" in data:
            self._ensure_marca_exists(data["marca_id"])
        if "propietarios_ids" in data:
            for persona_id in data["propietarios_ids"]:
                self._ensure_persona_exists(persona_id)
            return self._vehiculo_repository.set_propietarios(
                vehiculo_id, data.pop("propietarios_ids")
            )
        return self._vehiculo_repository.update(vehiculo_id, data)

    def delete(self, vehiculo_id: int) -> None:
        self.get(vehiculo_id)
        self._vehiculo_repository.delete(vehiculo_id)

    def add_propietario(self, vehiculo_id: int, persona_id: int) -> Vehiculo:
        self.get(vehiculo_id)
        self._ensure_persona_exists(persona_id)
        try:
            return self._vehiculo_repository.add_propietario(vehiculo_id, persona_id)
        except ValueError as exc:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=str(exc),
            ) from exc

    def _ensure_marca_exists(self, marca_id: int) -> None:
        marca = self._marca_repository.get(marca_id)
        if not marca:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND, detail="Marca no encontrada."
            )

    def _ensure_persona_exists(self, persona_id: int) -> None:
        persona = self._persona_repository.get(persona_id)
        if not persona:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND, detail="Persona no encontrada."
            )

