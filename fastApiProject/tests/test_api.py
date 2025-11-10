"""
Tests automatizados para la API de Gestión de Vehículos.
"""
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
    """Prepara la base de datos antes de cada test."""
    Base.metadata.drop_all(bind=engine)
    Base.metadata.create_all(bind=engine)
    yield
    Base.metadata.drop_all(bind=engine)


# ==================== TESTS DE MARCAS ====================

def test_crear_marca() -> None:
    """Test para crear una marca."""
    response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    assert response.status_code == 201
    data = response.json()
    assert data["nombre_marca"] == "Toyota"
    assert data["pais"] == "Japón"
    assert "id" in data


def test_listar_marcas() -> None:
    """Test para listar marcas."""
    # Crear algunas marcas
    client.post("/api/marcas/", json={"nombre_marca": "Toyota", "pais": "Japón"})
    client.post("/api/marcas/", json={"nombre_marca": "Ford", "pais": "EE.UU."})
    
    response = client.get("/api/marcas/")
    assert response.status_code == 200
    data = response.json()
    assert len(data) == 2
    assert any(m["nombre_marca"] == "Toyota" for m in data)
    assert any(m["nombre_marca"] == "Ford" for m in data)


def test_obtener_marca() -> None:
    """Test para obtener una marca por ID."""
    create_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = create_response.json()["id"]
    
    response = client.get(f"/api/marcas/{marca_id}")
    assert response.status_code == 200
    data = response.json()
    assert data["id"] == marca_id
    assert data["nombre_marca"] == "Toyota"


def test_obtener_marca_no_existe() -> None:
    """Test para obtener una marca que no existe."""
    response = client.get("/api/marcas/999")
    assert response.status_code == 404


def test_actualizar_marca() -> None:
    """Test para actualizar una marca."""
    create_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = create_response.json()["id"]
    
    response = client.put(
        f"/api/marcas/{marca_id}",
        json={"nombre_marca": "Toyota Motor", "pais": "Japón"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["nombre_marca"] == "Toyota Motor"


def test_eliminar_marca() -> None:
    """Test para eliminar una marca."""
    create_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = create_response.json()["id"]
    
    response = client.delete(f"/api/marcas/{marca_id}")
    assert response.status_code == 204
    
    # Verificar que fue eliminada
    get_response = client.get(f"/api/marcas/{marca_id}")
    assert get_response.status_code == 404


def test_no_permite_marcas_duplicadas() -> None:
    """Test que no permite crear marcas con el mismo nombre."""
    payload = {"nombre_marca": "Mazda", "pais": "Japón"}
    primera_respuesta = client.post("/api/marcas/", json=payload)
    assert primera_respuesta.status_code == 201
    
    segunda_respuesta = client.post("/api/marcas/", json=payload)
    assert segunda_respuesta.status_code == 400
    assert "Ya existe una marca con ese nombre" in segunda_respuesta.json()["detail"]


# ==================== TESTS DE PERSONAS ====================

def test_crear_persona() -> None:
    """Test para crear una persona."""
    response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    assert response.status_code == 201
    data = response.json()
    assert data["nombre"] == "Juan Pérez"
    assert data["cedula"] == "123456789"
    assert "id" in data


def test_listar_personas() -> None:
    """Test para listar personas."""
    client.post("/api/personas/", json={"nombre": "Juan Pérez", "cedula": "123456789"})
    client.post("/api/personas/", json={"nombre": "María García", "cedula": "987654321"})
    
    response = client.get("/api/personas/")
    assert response.status_code == 200
    data = response.json()
    assert len(data) == 2


def test_obtener_persona() -> None:
    """Test para obtener una persona por ID."""
    create_response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    persona_id = create_response.json()["id"]
    
    response = client.get(f"/api/personas/{persona_id}")
    assert response.status_code == 200
    data = response.json()
    assert data["id"] == persona_id
    assert data["nombre"] == "Juan Pérez"


def test_actualizar_persona() -> None:
    """Test para actualizar una persona."""
    create_response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    persona_id = create_response.json()["id"]
    
    response = client.put(
        f"/api/personas/{persona_id}",
        json={"nombre": "Juan Carlos Pérez"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["nombre"] == "Juan Carlos Pérez"


def test_eliminar_persona() -> None:
    """Test para eliminar una persona."""
    create_response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    persona_id = create_response.json()["id"]
    
    response = client.delete(f"/api/personas/{persona_id}")
    assert response.status_code == 204


def test_no_permite_cedulas_duplicadas() -> None:
    """Test que no permite crear personas con la misma cédula."""
    payload = {"nombre": "Juan Pérez", "cedula": "123456789"}
    primera_respuesta = client.post("/api/personas/", json=payload)
    assert primera_respuesta.status_code == 201
    
    segunda_respuesta = client.post("/api/personas/", json=payload)
    assert segunda_respuesta.status_code == 400
    assert "Ya existe una persona con esa cédula" in segunda_respuesta.json()["detail"]


# ==================== TESTS DE VEHÍCULOS ====================

def test_crear_vehiculo() -> None:
    """Test para crear un vehículo."""
    # Primero crear una marca
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    # Crear vehículo
    response = client.post(
        "/api/vehiculos/",
        json={
            "modelo": "Corolla",
            "marca_id": marca_id,
            "numero_puertas": 4,
            "color": "Rojo",
        },
    )
    assert response.status_code == 201
    data = response.json()
    assert data["modelo"] == "Corolla"
    assert data["numero_puertas"] == 4
    assert data["color"] == "Rojo"
    assert data["marca"]["id"] == marca_id


def test_listar_vehiculos() -> None:
    """Test para listar vehículos."""
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    client.post(
        "/api/vehiculos/",
        json={"modelo": "Corolla", "marca_id": marca_id, "numero_puertas": 4, "color": "Rojo"},
    )
    client.post(
        "/api/vehiculos/",
        json={"modelo": "Camry", "marca_id": marca_id, "numero_puertas": 4, "color": "Azul"},
    )
    
    response = client.get("/api/vehiculos/")
    assert response.status_code == 200
    data = response.json()
    assert len(data) == 2


def test_obtener_vehiculo() -> None:
    """Test para obtener un vehículo por ID."""
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    create_response = client.post(
        "/api/vehiculos/",
        json={"modelo": "Corolla", "marca_id": marca_id, "numero_puertas": 4, "color": "Rojo"},
    )
    vehiculo_id = create_response.json()["id"]
    
    response = client.get(f"/api/vehiculos/{vehiculo_id}")
    assert response.status_code == 200
    data = response.json()
    assert data["id"] == vehiculo_id
    assert data["modelo"] == "Corolla"


def test_actualizar_vehiculo() -> None:
    """Test para actualizar un vehículo."""
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    create_response = client.post(
        "/api/vehiculos/",
        json={"modelo": "Corolla", "marca_id": marca_id, "numero_puertas": 4, "color": "Rojo"},
    )
    vehiculo_id = create_response.json()["id"]
    
    response = client.put(
        f"/api/vehiculos/{vehiculo_id}",
        json={"color": "Verde"},
    )
    assert response.status_code == 200
    data = response.json()
    assert data["color"] == "Verde"


def test_eliminar_vehiculo() -> None:
    """Test para eliminar un vehículo."""
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    create_response = client.post(
        "/api/vehiculos/",
        json={"modelo": "Corolla", "marca_id": marca_id, "numero_puertas": 4, "color": "Rojo"},
    )
    vehiculo_id = create_response.json()["id"]
    
    response = client.delete(f"/api/vehiculos/{vehiculo_id}")
    assert response.status_code == 204


# ==================== TESTS DE RELACIONES ====================

def test_flujo_completo_crud() -> None:
    """Test del flujo completo CRUD con relaciones."""
    # Crear marca
    response_marca = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    assert response_marca.status_code == 201
    marca_id = response_marca.json()["id"]
    
    # Crear persona
    response_persona = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    assert response_persona.status_code == 201
    persona_id = response_persona.json()["id"]
    
    # Crear vehículo
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
    
    # Asignar propietario al vehículo
    response_propietario = client.post(
        f"/api/vehiculos/{vehiculo_id}/propietarios/",
        json={"persona_id": persona_id},
    )
    assert response_propietario.status_code == 201
    assert len(response_propietario.json()["propietarios"]) == 1
    assert response_propietario.json()["propietarios"][0]["id"] == persona_id
    
    # Obtener vehículos de una persona
    response_relacion = client.get(f"/api/personas/{persona_id}/vehiculos/")
    assert response_relacion.status_code == 200
    vehiculos = response_relacion.json()
    assert len(vehiculos) == 1
    assert vehiculos[0]["modelo"] == "Corolla"
    assert vehiculos[0]["id"] == vehiculo_id


def test_agregar_propietario_duplicado() -> None:
    """Test que no permite agregar el mismo propietario dos veces."""
    marca_response = client.post(
        "/api/marcas/",
        json={"nombre_marca": "Toyota", "pais": "Japón"},
    )
    marca_id = marca_response.json()["id"]
    
    persona_response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    persona_id = persona_response.json()["id"]
    
    vehiculo_response = client.post(
        "/api/vehiculos/",
        json={"modelo": "Corolla", "marca_id": marca_id, "numero_puertas": 4, "color": "Rojo"},
    )
    vehiculo_id = vehiculo_response.json()["id"]
    
    # Agregar propietario primera vez
    response1 = client.post(
        f"/api/vehiculos/{vehiculo_id}/propietarios/",
        json={"persona_id": persona_id},
    )
    assert response1.status_code == 201
    
    # Intentar agregar el mismo propietario otra vez
    response2 = client.post(
        f"/api/vehiculos/{vehiculo_id}/propietarios/",
        json={"persona_id": persona_id},
    )
    assert response2.status_code == 400
    assert "ya está asociado" in response2.json()["detail"].lower()


def test_obtener_vehiculos_de_persona_sin_vehiculos() -> None:
    """Test para obtener vehículos de una persona sin vehículos."""
    persona_response = client.post(
        "/api/personas/",
        json={"nombre": "Juan Pérez", "cedula": "123456789"},
    )
    persona_id = persona_response.json()["id"]
    
    response = client.get(f"/api/personas/{persona_id}/vehiculos/")
    assert response.status_code == 200
    assert response.json() == []


def test_crear_vehiculo_con_marca_inexistente() -> None:
    """Test que falla al crear vehículo con marca inexistente."""
    response = client.post(
        "/api/vehiculos/",
        json={
            "modelo": "Corolla",
            "marca_id": 999,
            "numero_puertas": 4,
            "color": "Rojo",
        },
    )
    assert response.status_code == 404
    assert "Marca no encontrada" in response.json()["detail"]
