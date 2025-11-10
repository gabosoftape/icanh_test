# -*- coding: utf-8 -*-

from odoo import models, fields, api


class MarcaVehiculo(models.Model):
    _name = 'icanh.marca.vehiculo'
    _description = 'Marca de Vehículo'
    _order = 'nombre_marca'

    name = fields.Char(string='Nombre de la Marca', required=True, index=True)
    nombre_marca = fields.Char(string='Nombre de la Marca', required=True, index=True)
    pais = fields.Char(string='País', required=True)
    vehiculo_ids = fields.One2many('icanh.vehiculo', 'marca_id', string='Vehículos')
    vehiculo_count = fields.Integer(string='Número de Vehículos', compute='_compute_vehiculo_count', store=False)

    _sql_constraints = [
        ('nombre_marca_unique', 'unique(nombre_marca)', 'Ya existe una marca con ese nombre.')
    ]

    @api.depends('vehiculo_ids')
    def _compute_vehiculo_count(self):
        for record in self:
            record.vehiculo_count = len(record.vehiculo_ids)

    @api.model
    def create(self, vals):
        if 'nombre_marca' in vals and not vals.get('name'):
            vals['name'] = vals['nombre_marca']
        return super(MarcaVehiculo, self).create(vals)

    def write(self, vals):
        if 'nombre_marca' in vals:
            vals['name'] = vals['nombre_marca']
        return super(MarcaVehiculo, self).write(vals)

