# -*- coding: utf-8 -*-

import json
import logging
from odoo import http
from odoo.http import request, Response

_logger = logging.getLogger(__name__)


class IcanhApiController(http.Controller):
    """Controlador API REST para ICANH con prefijo /api/icanh"""

    def _json_response(self, data=None, status=200, message=None):
        """Helper para crear respuestas JSON"""
        response_data = {
            'status': 'success' if status < 400 else 'error',
            'message': message,
            'data': data
        }
        return request.make_response(
            json.dumps(response_data, ensure_ascii=False),
            headers=[('Content-Type', 'application/json')],
            status=status
        )

    def _get_error_response(self, message, status=400):
        return self._json_response(data=None, status=status, message=message)

    # ==================== MARCAS ====================

    @http.route('/api/icanh/marcas', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def list_marcas(self, skip=0, limit=100, **kwargs):
        """Lista todas las marcas"""
        try:
            skip = int(skip)
            limit = int(limit)
            marcas = request.env['icanh.marca.vehiculo'].sudo().search([], offset=skip, limit=limit)
            data = [{
                'id': marca.id,
                'nombre_marca': marca.nombre_marca,
                'pais': marca.pais,
            } for marca in marcas]
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error listing marcas: {str(e)}")
            return self._get_error_response(f"Error al listar marcas: {str(e)}", 500)

    @http.route('/api/icanh/marcas/<int:marca_id>', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def get_marca(self, marca_id, **kwargs):
        """Obtiene una marca por ID"""
        try:
            marca = request.env['icanh.marca.vehiculo'].sudo().browse(marca_id)
            if not marca.exists():
                return self._get_error_response('Marca no encontrada', 404)
            data = {
                'id': marca.id,
                'nombre_marca': marca.nombre_marca,
                'pais': marca.pais,
            }
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error getting marca: {str(e)}")
            return self._get_error_response(f"Error al obtener marca: {str(e)}", 500)

    @http.route('/api/icanh/marcas', type='http', auth='public', methods=['POST'], csrf=False, cors='*')
    def create_marca(self, **kwargs):
        """Crea una nueva marca"""
        try:
            data = json.loads(request.httprequest.data.decode('utf-8'))
            if not data.get('nombre_marca') or not data.get('pais'):
                return self._get_error_response('nombre_marca y pais son requeridos', 400)
            
            marca = request.env['icanh.marca.vehiculo'].sudo().create({
                'nombre_marca': data['nombre_marca'],
                'pais': data['pais'],
            })
            response_data = {
                'id': marca.id,
                'nombre_marca': marca.nombre_marca,
                'pais': marca.pais,
            }
            return self._json_response(data=response_data, status=201, message='Marca creada exitosamente')
        except Exception as e:
            _logger.error(f"Error creating marca: {str(e)}")
            return self._get_error_response(f"Error al crear marca: {str(e)}", 500)

    @http.route('/api/icanh/marcas/<int:marca_id>', type='http', auth='public', methods=['PUT'], csrf=False, cors='*')
    def update_marca(self, marca_id, **kwargs):
        """Actualiza una marca"""
        try:
            marca = request.env['icanh.marca.vehiculo'].sudo().browse(marca_id)
            if not marca.exists():
                return self._get_error_response('Marca no encontrada', 404)
            
            data = json.loads(request.httprequest.data.decode('utf-8'))
            update_vals = {}
            if 'nombre_marca' in data:
                update_vals['nombre_marca'] = data['nombre_marca']
            if 'pais' in data:
                update_vals['pais'] = data['pais']
            
            marca.write(update_vals)
            response_data = {
                'id': marca.id,
                'nombre_marca': marca.nombre_marca,
                'pais': marca.pais,
            }
            return self._json_response(data=response_data, status=200, message='Marca actualizada exitosamente')
        except Exception as e:
            _logger.error(f"Error updating marca: {str(e)}")
            return self._get_error_response(f"Error al actualizar marca: {str(e)}", 500)

    @http.route('/api/icanh/marcas/<int:marca_id>', type='http', auth='public', methods=['DELETE'], csrf=False, cors='*')
    def delete_marca(self, marca_id, **kwargs):
        """Elimina una marca"""
        try:
            marca = request.env['icanh.marca.vehiculo'].sudo().browse(marca_id)
            if not marca.exists():
                return self._get_error_response('Marca no encontrada', 404)
            marca.unlink()
            return self._json_response(data=None, status=204, message='Marca eliminada exitosamente')
        except Exception as e:
            _logger.error(f"Error deleting marca: {str(e)}")
            return self._get_error_response(f"Error al eliminar marca: {str(e)}", 500)

    # ==================== PERSONAS ====================

    @http.route('/api/icanh/personas', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def list_personas(self, skip=0, limit=100, **kwargs):
        """Lista todas las personas"""
        try:
            skip = int(skip)
            limit = int(limit)
            personas = request.env['icanh.persona'].sudo().search([], offset=skip, limit=limit)
            data = [{
                'id': persona.id,
                'nombre': persona.nombre,
                'cedula': persona.cedula,
            } for persona in personas]
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error listing personas: {str(e)}")
            return self._get_error_response(f"Error al listar personas: {str(e)}", 500)

    @http.route('/api/icanh/personas/<int:persona_id>', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def get_persona(self, persona_id, **kwargs):
        """Obtiene una persona por ID"""
        try:
            persona = request.env['icanh.persona'].sudo().browse(persona_id)
            if not persona.exists():
                return self._get_error_response('Persona no encontrada', 404)
            data = {
                'id': persona.id,
                'nombre': persona.nombre,
                'cedula': persona.cedula,
            }
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error getting persona: {str(e)}")
            return self._get_error_response(f"Error al obtener persona: {str(e)}", 500)

    @http.route('/api/icanh/personas', type='http', auth='public', methods=['POST'], csrf=False, cors='*')
    def create_persona(self, **kwargs):
        """Crea una nueva persona"""
        try:
            data = json.loads(request.httprequest.data.decode('utf-8'))
            if not data.get('nombre') or not data.get('cedula'):
                return self._get_error_response('nombre y cedula son requeridos', 400)
            
            persona = request.env['icanh.persona'].sudo().create({
                'nombre': data['nombre'],
                'cedula': data['cedula'],
            })
            response_data = {
                'id': persona.id,
                'nombre': persona.nombre,
                'cedula': persona.cedula,
            }
            return self._json_response(data=response_data, status=201, message='Persona creada exitosamente')
        except Exception as e:
            _logger.error(f"Error creating persona: {str(e)}")
            return self._get_error_response(f"Error al crear persona: {str(e)}", 500)

    @http.route('/api/icanh/personas/<int:persona_id>', type='http', auth='public', methods=['PUT'], csrf=False, cors='*')
    def update_persona(self, persona_id, **kwargs):
        """Actualiza una persona"""
        try:
            persona = request.env['icanh.persona'].sudo().browse(persona_id)
            if not persona.exists():
                return self._get_error_response('Persona no encontrada', 404)
            
            data = json.loads(request.httprequest.data.decode('utf-8'))
            update_vals = {}
            if 'nombre' in data:
                update_vals['nombre'] = data['nombre']
            if 'cedula' in data:
                update_vals['cedula'] = data['cedula']
            
            persona.write(update_vals)
            response_data = {
                'id': persona.id,
                'nombre': persona.nombre,
                'cedula': persona.cedula,
            }
            return self._json_response(data=response_data, status=200, message='Persona actualizada exitosamente')
        except Exception as e:
            _logger.error(f"Error updating persona: {str(e)}")
            return self._get_error_response(f"Error al actualizar persona: {str(e)}", 500)

    @http.route('/api/icanh/personas/<int:persona_id>', type='http', auth='public', methods=['DELETE'], csrf=False, cors='*')
    def delete_persona(self, persona_id, **kwargs):
        """Elimina una persona"""
        try:
            persona = request.env['icanh.persona'].sudo().browse(persona_id)
            if not persona.exists():
                return self._get_error_response('Persona no encontrada', 404)
            persona.unlink()
            return self._json_response(data=None, status=204, message='Persona eliminada exitosamente')
        except Exception as e:
            _logger.error(f"Error deleting persona: {str(e)}")
            return self._get_error_response(f"Error al eliminar persona: {str(e)}", 500)

    @http.route('/api/icanh/personas/<int:persona_id>/vehiculos', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def get_persona_vehiculos(self, persona_id, **kwargs):
        """Obtiene los vehículos de una persona"""
        try:
            persona = request.env['icanh.persona'].sudo().browse(persona_id)
            if not persona.exists():
                return self._get_error_response('Persona no encontrada', 404)
            
            data = [{
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca if vehiculo.marca_id else None,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
            } for vehiculo in persona.vehiculo_ids]
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error getting persona vehiculos: {str(e)}")
            return self._get_error_response(f"Error al obtener vehículos: {str(e)}", 500)

    # ==================== VEHÍCULOS ====================

    @http.route('/api/icanh/vehiculos', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def list_vehiculos(self, skip=0, limit=100, **kwargs):
        """Lista todos los vehículos"""
        try:
            skip = int(skip)
            limit = int(limit)
            vehiculos = request.env['icanh.vehiculo'].sudo().search([], offset=skip, limit=limit)
            data = [{
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca if vehiculo.marca_id else None,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
                'propietarios_ids': vehiculo.propietario_ids.ids,
            } for vehiculo in vehiculos]
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error listing vehiculos: {str(e)}")
            return self._get_error_response(f"Error al listar vehículos: {str(e)}", 500)

    @http.route('/api/icanh/vehiculos/<int:vehiculo_id>', type='http', auth='public', methods=['GET'], csrf=False, cors='*')
    def get_vehiculo(self, vehiculo_id, **kwargs):
        """Obtiene un vehículo por ID"""
        try:
            vehiculo = request.env['icanh.vehiculo'].sudo().browse(vehiculo_id)
            if not vehiculo.exists():
                return self._get_error_response('Vehículo no encontrado', 404)
            data = {
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca if vehiculo.marca_id else None,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
                'propietarios_ids': vehiculo.propietario_ids.ids,
            }
            return self._json_response(data=data, status=200)
        except Exception as e:
            _logger.error(f"Error getting vehiculo: {str(e)}")
            return self._get_error_response(f"Error al obtener vehículo: {str(e)}", 500)

    @http.route('/api/icanh/vehiculos', type='http', auth='public', methods=['POST'], csrf=False, cors='*')
    def create_vehiculo(self, **kwargs):
        """Crea un nuevo vehículo"""
        try:
            data = json.loads(request.httprequest.data.decode('utf-8'))
            if not all(k in data for k in ['modelo', 'marca_id', 'numero_puertas', 'color']):
                return self._get_error_response('modelo, marca_id, numero_puertas y color son requeridos', 400)
            
            # Verificar que la marca existe
            marca = request.env['icanh.marca.vehiculo'].sudo().browse(data['marca_id'])
            if not marca.exists():
                return self._get_error_response('Marca no encontrada', 404)
            
            create_vals = {
                'modelo': data['modelo'],
                'marca_id': data['marca_id'],
                'numero_puertas': data['numero_puertas'],
                'color': data['color'],
            }
            
            if 'propietarios_ids' in data and data['propietarios_ids']:
                create_vals['propietario_ids'] = [(6, 0, data['propietarios_ids'])]
            
            vehiculo = request.env['icanh.vehiculo'].sudo().create(create_vals)
            response_data = {
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
                'propietarios_ids': vehiculo.propietario_ids.ids,
            }
            return self._json_response(data=response_data, status=201, message='Vehículo creado exitosamente')
        except Exception as e:
            _logger.error(f"Error creating vehiculo: {str(e)}")
            return self._get_error_response(f"Error al crear vehículo: {str(e)}", 500)

    @http.route('/api/icanh/vehiculos/<int:vehiculo_id>', type='http', auth='public', methods=['PUT'], csrf=False, cors='*')
    def update_vehiculo(self, vehiculo_id, **kwargs):
        """Actualiza un vehículo"""
        try:
            vehiculo = request.env['icanh.vehiculo'].sudo().browse(vehiculo_id)
            if not vehiculo.exists():
                return self._get_error_response('Vehículo no encontrado', 404)
            
            data = json.loads(request.httprequest.data.decode('utf-8'))
            update_vals = {}
            if 'modelo' in data:
                update_vals['modelo'] = data['modelo']
            if 'marca_id' in data:
                marca = request.env['icanh.marca.vehiculo'].sudo().browse(data['marca_id'])
                if not marca.exists():
                    return self._get_error_response('Marca no encontrada', 404)
                update_vals['marca_id'] = data['marca_id']
            if 'numero_puertas' in data:
                update_vals['numero_puertas'] = data['numero_puertas']
            if 'color' in data:
                update_vals['color'] = data['color']
            if 'propietarios_ids' in data:
                update_vals['propietario_ids'] = [(6, 0, data['propietarios_ids'])]
            
            vehiculo.write(update_vals)
            response_data = {
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca if vehiculo.marca_id else None,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
                'propietarios_ids': vehiculo.propietario_ids.ids,
            }
            return self._json_response(data=response_data, status=200, message='Vehículo actualizado exitosamente')
        except Exception as e:
            _logger.error(f"Error updating vehiculo: {str(e)}")
            return self._get_error_response(f"Error al actualizar vehículo: {str(e)}", 500)

    @http.route('/api/icanh/vehiculos/<int:vehiculo_id>', type='http', auth='public', methods=['DELETE'], csrf=False, cors='*')
    def delete_vehiculo(self, vehiculo_id, **kwargs):
        """Elimina un vehículo"""
        try:
            vehiculo = request.env['icanh.vehiculo'].sudo().browse(vehiculo_id)
            if not vehiculo.exists():
                return self._get_error_response('Vehículo no encontrado', 404)
            vehiculo.unlink()
            return self._json_response(data=None, status=204, message='Vehículo eliminado exitosamente')
        except Exception as e:
            _logger.error(f"Error deleting vehiculo: {str(e)}")
            return self._get_error_response(f"Error al eliminar vehículo: {str(e)}", 500)

    @http.route('/api/icanh/vehiculos/<int:vehiculo_id>/propietarios', type='http', auth='public', methods=['POST'], csrf=False, cors='*')
    def add_propietario(self, vehiculo_id, **kwargs):
        """Agrega un propietario a un vehículo"""
        try:
            vehiculo = request.env['icanh.vehiculo'].sudo().browse(vehiculo_id)
            if not vehiculo.exists():
                return self._get_error_response('Vehículo no encontrado', 404)
            
            data = json.loads(request.httprequest.data.decode('utf-8'))
            if 'persona_id' not in data:
                return self._get_error_response('persona_id es requerido', 400)
            
            persona = request.env['icanh.persona'].sudo().browse(data['persona_id'])
            if not persona.exists():
                return self._get_error_response('Persona no encontrada', 404)
            
            if persona.id in vehiculo.propietario_ids.ids:
                return self._get_error_response('La persona ya es propietaria de este vehículo', 400)
            
            vehiculo.write({
                'propietario_ids': [(4, persona.id)]
            })
            
            response_data = {
                'id': vehiculo.id,
                'modelo': vehiculo.modelo,
                'marca_id': vehiculo.marca_id.id,
                'marca': vehiculo.marca_id.nombre_marca if vehiculo.marca_id else None,
                'numero_puertas': vehiculo.numero_puertas,
                'color': vehiculo.color,
                'propietarios_ids': vehiculo.propietario_ids.ids,
            }
            return self._json_response(data=response_data, status=201, message='Propietario agregado exitosamente')
        except Exception as e:
            _logger.error(f"Error adding propietario: {str(e)}")
            return self._get_error_response(f"Error al agregar propietario: {str(e)}", 500)

