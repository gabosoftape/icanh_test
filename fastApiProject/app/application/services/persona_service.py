from __future__ import annotations

from typing import List

from fastapi import HTTPException, status

from app.domain.entities import Persona
from app.domain.repositories import PersonaRepository


class PersonaService:
    def __init__(self, repository: PersonaRepository) -> None:
        self._repository = repository

    def create(self, persona: Persona) -> Persona:
        try:
            return self._repository.create(persona)
        except ValueError as exc:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=str(exc),
            ) from exc

    def list(self, skip: int, limit: int) -> List[Persona]:
        return self._repository.list(skip, limit)

    def get(self, persona_id: int) -> Persona:
        persona = self._repository.get(persona_id)
        if not persona:
            raise HTTPException(
                status_code=status.HTTP_404_NOT_FOUND, detail="Persona no encontrada."
            )
        return persona

    def update(self, persona_id: int, data: dict) -> Persona:
        self.get(persona_id)
        try:
            return self._repository.update(persona_id, data)
        except ValueError as exc:
            raise HTTPException(
                status_code=status.HTTP_400_BAD_REQUEST,
                detail=str(exc),
            ) from exc

    def delete(self, persona_id: int) -> None:
        self.get(persona_id)
        self._repository.delete(persona_id)

