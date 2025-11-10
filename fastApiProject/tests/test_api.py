from __future__ import annotations

import os
from pathlib import Path
from typing import Generator

import pytest
import sys
from fastapi.testclient import TestClient
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker
from sqlalchemy.pool import StaticPool

sys.path.insert(0, str(Path(__file__).resolve().parent.parent))

from app.core.config import get_settings
from app.infrastructure.db.base import Base
from app.infrastructure.db.session import get_db
from main import app


settings = get_settings()
SQLALCHEMY_TEST_DATABASE_URL = os.getenv("TEST_DATABASE_URL", settings.test_database_url)

engine = create_engine(
    SQLALCHEMY_TEST_DATABASE_URL,
    connect_args={"check_same_thread": False} if SQLALCHEMY_TEST_DATABASE_URL.startswith("sqlite") else {},
    poolclass=StaticPool if SQLALCHEMY_TEST_DATABASE_URL.startswith("sqlite") else None,
)
TestingSessionLocal = sessionmaker(
    bind=engine, autocommit=False, autoflush=False, future=True
)


def override_get_db() -> Generator:
    db = TestingSessionLocal()
    try:
        yield db
    finally:
        db.close()


app.dependency_overrides[get_db] = override_get_db
client = TestClient(app)


@pytest.fixture(autouse=True)
def prepare_database() -> Generator:
    Base.metadata.drop_all(bind=engine)
    Base.metadata.create_all(bind=engine)
    yield
    Base.metadata.drop_all(bind=engine)


def test_flujo_completo_crud() -> None:
    response_marca = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    assert response_marca.status_code == 201
    marca_id = response_marca.json()["id"]

    response_persona = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    assert response_persona.status_code == 201
    persona_id = response_persona.json()["id"]

    response_vehiculo = client.post(
        "/api/vehiculos/",
        json={
            "modelo": "Corolla",
            "marca_id": marca_id,
            "numero_puertas": 4,
            "color": "Rojo",
        },
    )
    assert response_vehiculo.status_code == 201
    vehiculo_id = response_vehiculo.json()["id"]

    response_propietario = client.post(
        f"/api/vehiculos/{vehiculo_id}/propietarios/",
        json={"persona_id": persona_id},
    )
    assert response_propietario.status_code == 201
    assert len(response_propietario.json()["propietarios"]) == 1

    response_relacion = client.get(f"/api/personas/{persona_id}/vehiculos/")
    assert response_relacion.status_code == 200
    vehiculos = response_relacion.json()
    assert len(vehiculos) == 1
    assert vehiculos[0]["modelo"] == "Corolla"


def test_no_permite_marcas_duplicadas() -> None:
    payload = {"nombre_marca": "Mazda", "pais": "Japón"}
    primera_respuesta = client.post("/api/marcas/", json=payload)
    assert primera_respuesta.status_code == 201

    segunda_respuesta = client.post("/api/marcas/", json=payload)
    assert segunda_respuesta.status_code == 400
    assert segunda_respuesta.json()["detail"] == "Ya existe una marca con ese nombre."

