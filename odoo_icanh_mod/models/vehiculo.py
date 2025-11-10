# -*- coding: utf-8 -*-

from odoo import models, fields, api


class Vehiculo(models.Model):
    _name = 'icanh.vehiculo'
    _description = 'Vehículo'
    _order = 'modelo'

    name = fields.Char(string='Nombre', compute='_compute_name', store=True)
    modelo = fields.Char(string='Modelo', required=True, index=True)
    marca_id = fields.Many2one('icanh.marca.vehiculo', string='Marca', required=True, ondelete='restrict')
    numero_puertas = fields.Integer(string='Número de Puertas', required=True, default=4)
    color = fields.Char(string='Color', required=True)
    propietario_ids = fields.Many2many(
        'icanh.persona',
        'vehiculo_propietario_rel',
        'vehiculo_id',
        'persona_id',
        string='Propietarios'
    )
    propietario_count = fields.Integer(string='Número de Propietarios', compute='_compute_propietario_count', store=False)

    _sql_constraints = [
        ('numero_puertas_check', 'CHECK(numero_puertas >= 2 AND numero_puertas <= 6)', 
         'El número de puertas debe estar entre 2 y 6.')
    ]

    @api.depends('modelo', 'marca_id')
    def _compute_name(self):
        for record in self:
            if record.modelo and record.marca_id:
                record.name = f"{record.marca_id.nombre_marca} {record.modelo}"
            elif record.modelo:
                record.name = record.modelo
            else:
                record.name = 'Nuevo Vehículo'

    @api.depends('propietario_ids')
    def _compute_propietario_count(self):
        for record in self:
            record.propietario_count = len(record.propietario_ids)

