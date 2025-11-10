# -*- coding: utf-8 -*-

from odoo import models, fields, api


class Persona(models.Model):
    _name = 'icanh.persona'
    _description = 'Persona'
    _order = 'nombre'

    name = fields.Char(string='Nombre', required=True, index=True)
    nombre = fields.Char(string='Nombre', required=True, index=True)
    cedula = fields.Char(string='Cédula', required=True, index=True)
    vehiculo_ids = fields.Many2many(
        'icanh.vehiculo',
        'vehiculo_propietario_rel',
        'persona_id',
        'vehiculo_id',
        string='Vehículos Propios'
    )
    vehiculo_count = fields.Integer(string='Número de Vehículos', compute='_compute_vehiculo_count', store=False)

    _sql_constraints = [
        ('cedula_unique', 'unique(cedula)', 'Ya existe una persona con esa cédula.')
    ]

    @api.depends('vehiculo_ids')
    def _compute_vehiculo_count(self):
        for record in self:
            record.vehiculo_count = len(record.vehiculo_ids)

    @api.model
    def create(self, vals):
        if 'nombre' in vals and not vals.get('name'):
            vals['name'] = vals['nombre']
        return super(Persona, self).create(vals)

    def write(self, vals):
        if 'nombre' in vals:
            vals['name'] = vals['nombre']
        return super(Persona, self).write(vals)

