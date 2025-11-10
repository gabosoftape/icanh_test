# -*- coding: utf-8 -*-
{
    'name': 'Gestión de Vehículos ICANH',
    'version': '18.0.1.0.0',
    'category': 'Custom',
    'summary': 'Módulo para gestionar marcas, personas y vehículos con API REST',
    'description': """
        Módulo de Gestión de Vehículos ICANH
        ====================================
        
        Este módulo permite gestionar:
        - Marcas de vehículos
        - Personas
        - Vehículos y sus relaciones
        
        Incluye:
        - Vistas Tree, Form y Kanban para cada modelo
        - API REST para acceso externo con prefijo /api/icanh
        - CRUD completo para todas las entidades
    """,
    'author': 'DUMAR PABON',
    'website': 'https://arpadine.com',
    'depends': ['base'],
    'data': [
        'security/ir.model.access.csv',
        'data/vehiculo_propietario_data.xml',
        'views/marca_vehiculo_views.xml',
        'views/persona_views.xml',
        'views/vehiculo_views.xml',
        'views/menu_views.xml',
    ],
    'images': ['static/description/banner.jpg'],
    'installable': True,
    'application': True,
    'auto_install': False,
    'license': 'LGPL-3',
}

