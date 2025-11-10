# Prueba Técnica ICANH - Gestión de Vehículos

Este repositorio contiene una prueba técnica que implementa un sistema de gestión de vehículos, personas y marcas de vehículos. La solución se divide en **3 proyectos independientes**, cada uno implementando la misma funcionalidad utilizando diferentes tecnologías y frameworks.

## 📦 Estructura del Repositorio

El repositorio está organizado en tres proyectos principales:

1. **FastAPI** (`fastApiProject/`) - Implementación con Python y FastAPI
2. **Laravel** (`laravelProject/`) - Implementación con PHP y Laravel
3. **Odoo** (`odoo_icanh_mod/`) - Módulo para Odoo 18

---

## 🐍 FastAPI Project

**Ubicación:** `fastApiProject/`

### Resumen

API RESTful desarrollada con **FastAPI** siguiendo **arquitectura hexagonal** y principios **SOLID** para gestionar marcas de vehículos, personas y su relación de propiedad con vehículos.

### Características principales:

- **Arquitectura Hexagonal**: Separación clara entre dominio, aplicación, infraestructura y API
- **Base de datos**: PostgreSQL con SQLAlchemy ORM
- **Documentación**: Swagger/ReDoc automática
- **Testing**: Suite completa de pruebas automatizadas con pytest
- **Docker**: Configuración completa con docker-compose
- **Endpoints CRUD**: Completos para Marcas, Personas y Vehículos

### Tecnologías:
- Python 3.10+
- FastAPI
- PostgreSQL
- SQLAlchemy
- Pytest

### Documentación completa:
Ver [fastApiProject/README.md](fastApiProject/README.md)

---

## 🐘 Laravel Project

**Ubicación:** `laravelProject/`

### Resumen

API RESTful para gestionar marcas de vehículos, personas y su relación de propiedad con vehículos, implementada con **Laravel** siguiendo **arquitectura hexagonal** y principios **SOLID**.

### Características principales:

- **Arquitectura Hexagonal**: Capas separadas de Domain, Application, Infrastructure y API
- **Frontend**: Interfaz web desarrollada con React/TypeScript e Inertia.js
- **Base de datos**: PostgreSQL con Eloquent ORM
- **Autenticación**: Sistema de autenticación con Laravel Fortify
- **Documentación**: Swagger/OpenAPI integrado
- **Testing**: Suite de pruebas con PHPUnit
- **Docker**: Configuración completa con Nginx y PHP-FPM

### Tecnologías:
- PHP 8.2+
- Laravel
- PostgreSQL
- React/TypeScript
- Inertia.js
- Swagger/OpenAPI

### Documentación completa:
Ver [laravelProject/README.md](laravelProject/README.md)

---

## 🔧 Odoo Module

**Ubicación:** `odoo_icanh_mod/`

### Resumen

Módulo completo para **Odoo 18** que permite gestionar marcas de vehículos, personas y vehículos con API REST integrada.

### Características principales:

- **3 Modelos principales**: Marcas de Vehículos, Personas y Vehículos
- **Vistas completas**: Tree, Form, Kanban y Search
- **API REST**: Endpoints públicos con prefijo `/api/icanh`
- **Autenticación**: Pública (`auth='public'`) - No requiere autenticación
- **CORS**: Soporte para acceso desde otros dominios
- **Integración**: Módulo nativo de Odoo con todas las funcionalidades del ERP

### Tecnologías:
- Odoo 18
- Python
- XML para vistas
- PostgreSQL (base de datos de Odoo)

### Documentación completa:
Ver [odoo_icanh_mod/README.md](odoo_icanh_mod/README.md)

---

## 🎯 Funcionalidad Común

Los tres proyectos implementan la misma funcionalidad:

### Entidades:
- **Marcas de Vehículos**: Gestión de marcas con país de origen
- **Personas**: Gestión de personas con cédula de identidad
- **Vehículos**: Gestión de vehículos con modelo, marca, número de puertas y color

### Operaciones:
- **CRUD completo** para todas las entidades
- **Relaciones**: Asignación de propietarios a vehículos
- **Consultas**: Obtener vehículos por persona y viceversa

---

## 📝 Notas

Este proyecto es parte de una **prueba técnica** que demuestra la implementación de la misma funcionalidad utilizando diferentes tecnologías y arquitecturas, cada una con sus propias ventajas y casos de uso.

---

## 👤 Autor

DUMAR PABON

