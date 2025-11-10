import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';

interface Marca {
    id: number;
    nombre_marca: string;
}

interface Persona {
    id: number;
    nombre: string;
    cedula: string;
}

interface Vehiculo {
    id: number;
    modelo: string;
    marca_id: number;
    numero_puertas: number;
    color: string;
    propietarios_ids: number[];
}

interface Props {
    vehiculo: Vehiculo;
    marcas: Marca[];
    personas: Persona[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Vehículos',
        href: '/vehiculos',
    },
    {
        title: 'Editar',
    },
];

export default function VehiculosEdit({ vehiculo, marcas, personas }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        modelo: vehiculo.modelo,
        marca_id: vehiculo.marca_id,
        numero_puertas: vehiculo.numero_puertas.toString(),
        color: vehiculo.color,
        propietarios_ids: vehiculo.propietarios_ids,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/vehiculos/${vehiculo.id}`);
    };

    const togglePropietario = (personaId: number) => {
        setData('propietarios_ids', (prev) =>
            prev.includes(personaId)
                ? prev.filter((id) => id !== personaId)
                : [...prev, personaId]
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Editar Vehículo - ${vehiculo.modelo}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/vehiculos">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold">Editar Vehículo</h1>
                        <p className="text-muted-foreground text-sm">
                            Modifica los datos del vehículo
                        </p>
                    </div>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información del Vehículo</CardTitle>
                        <CardDescription>
                            Actualiza los datos del vehículo
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="modelo">Modelo *</Label>
                                <Input
                                    id="modelo"
                                    value={data.modelo}
                                    onChange={(e) => setData('modelo', e.target.value)}
                                    placeholder="Ej: Corolla, Focus, X5"
                                    required
                                    aria-invalid={errors.modelo ? 'true' : 'false'}
                                />
                                {errors.modelo && (
                                    <p className="text-destructive text-sm">
                                        {errors.modelo}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="marca_id">Marca *</Label>
                                <Select
                                    value={data.marca_id.toString()}
                                    onValueChange={(value) =>
                                        setData('marca_id', parseInt(value))
                                    }
                                >
                                    <SelectTrigger>
                                        <SelectValue placeholder="Selecciona una marca" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {marcas.map((marca) => (
                                            <SelectItem
                                                key={marca.id}
                                                value={marca.id.toString()}
                                            >
                                                {marca.nombre_marca}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.marca_id && (
                                    <p className="text-destructive text-sm">
                                        {errors.marca_id}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="numero_puertas">
                                    Número de Puertas *
                                </Label>
                                <Input
                                    id="numero_puertas"
                                    type="number"
                                    min="2"
                                    max="5"
                                    value={data.numero_puertas}
                                    onChange={(e) =>
                                        setData('numero_puertas', e.target.value)
                                    }
                                    placeholder="2, 3, 4 o 5"
                                    required
                                    aria-invalid={errors.numero_puertas ? 'true' : 'false'}
                                />
                                {errors.numero_puertas && (
                                    <p className="text-destructive text-sm">
                                        {errors.numero_puertas}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="color">Color *</Label>
                                <Input
                                    id="color"
                                    value={data.color}
                                    onChange={(e) => setData('color', e.target.value)}
                                    placeholder="Ej: Rojo, Azul, Negro"
                                    required
                                    aria-invalid={errors.color ? 'true' : 'false'}
                                />
                                {errors.color && (
                                    <p className="text-destructive text-sm">
                                        {errors.color}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label>Propietarios</Label>
                                <div className="max-h-48 space-y-2 overflow-y-auto rounded-md border p-4">
                                    {personas.map((persona) => (
                                        <div
                                            key={persona.id}
                                            className="flex items-center space-x-2"
                                        >
                                            <Checkbox
                                                id={`propietario-${persona.id}`}
                                                checked={data.propietarios_ids.includes(
                                                    persona.id
                                                )}
                                                onCheckedChange={() =>
                                                    togglePropietario(persona.id)
                                                }
                                            />
                                            <Label
                                                htmlFor={`propietario-${persona.id}`}
                                                className="cursor-pointer text-sm font-normal"
                                            >
                                                {persona.nombre} ({persona.cedula})
                                            </Label>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="flex gap-4 pt-4">
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Guardando...' : 'Guardar Cambios'}
                                </Button>
                                <Link href="/vehiculos">
                                    <Button type="button" variant="outline">
                                        Cancelar
                                    </Button>
                                </Link>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}

