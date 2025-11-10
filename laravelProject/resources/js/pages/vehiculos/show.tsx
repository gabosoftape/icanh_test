import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Edit } from 'lucide-react';

interface Marca {
    id: number;
    nombre_marca: string;
    pais: string;
}

interface Propietario {
    id: number;
    nombre: string;
    cedula: string;
}

interface Vehiculo {
    id: number;
    modelo: string;
    marca: Marca | null;
    numero_puertas: number;
    color: string;
    propietarios: Propietario[];
}

interface Props {
    vehiculo: Vehiculo;
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
        title: vehiculo?.modelo || 'Detalles',
    },
];

export default function VehiculosShow({ vehiculo }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Vehículo - ${vehiculo.modelo}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/vehiculos">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div className="flex-1">
                        <h1 className="text-2xl font-semibold">{vehiculo.modelo}</h1>
                        <p className="text-muted-foreground text-sm">
                            Detalles del vehículo
                        </p>
                    </div>
                    <Link href={`/vehiculos/${vehiculo.id}/edit`}>
                        <Button>
                            <Edit className="h-4 w-4 mr-2" />
                            Editar
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Información del Vehículo</CardTitle>
                            <CardDescription>
                                Detalles completos del vehículo
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <Label className="text-muted-foreground text-sm">
                                    Modelo
                                </Label>
                                <p className="text-lg font-medium">{vehiculo.modelo}</p>
                            </div>
                            <div>
                                <Label className="text-muted-foreground text-sm">Marca</Label>
                                <p className="text-lg font-medium">
                                    {vehiculo.marca?.nombre_marca || 'N/A'}
                                </p>
                                {vehiculo.marca?.pais && (
                                    <p className="text-muted-foreground text-sm">
                                        País: {vehiculo.marca.pais}
                                    </p>
                                )}
                            </div>
                            <div>
                                <Label className="text-muted-foreground text-sm">
                                    Número de Puertas
                                </Label>
                                <p className="text-lg font-medium">
                                    {vehiculo.numero_puertas}
                                </p>
                            </div>
                            <div>
                                <Label className="text-muted-foreground text-sm">Color</Label>
                                <p className="text-lg font-medium">{vehiculo.color}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Propietarios</CardTitle>
                            <CardDescription>
                                Personas registradas como propietarias
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {vehiculo.propietarios.length === 0 ? (
                                <p className="text-muted-foreground text-sm">
                                    No hay propietarios registrados
                                </p>
                            ) : (
                                <div className="space-y-2">
                                    {vehiculo.propietarios.map((propietario) => (
                                        <div
                                            key={propietario.id}
                                            className="rounded-md border p-3"
                                        >
                                            <p className="font-medium">{propietario.nombre}</p>
                                            <p className="text-muted-foreground text-sm">
                                                Cédula: {propietario.cedula}
                                            </p>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}

function Label({ className, ...props }: React.ComponentProps<'label'>) {
    return (
        <label
            className={`block text-sm font-medium ${className || ''}`}
            {...props}
        />
    );
}

