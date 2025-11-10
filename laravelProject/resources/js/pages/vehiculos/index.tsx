import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Plus, Edit, Trash2, Car } from 'lucide-react';
import { useEffect, useState } from 'react';
import { type SharedData } from '@/types';

interface Marca {
    id: number;
    nombre_marca: string;
}

interface Vehiculo {
    id: number;
    modelo: string;
    marca: Marca | null;
    numero_puertas: number;
    color: string;
}

interface Props {
    vehiculos: Vehiculo[];
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
];

export default function VehiculosIndex({ vehiculos: initialVehiculos }: Props) {
    const [deleting, setDeleting] = useState<number | null>(null);
    const { flash } = usePage<SharedData>().props;
    const [vehiculos, setVehiculos] = useState(initialVehiculos);

    // Actualizar vehículos cuando cambian los props
    useEffect(() => {
        setVehiculos(initialVehiculos);
    }, [initialVehiculos]);

    // Recargar datos cuando hay un mensaje flash de éxito
    useEffect(() => {
        if (flash?.success) {
            router.reload({ only: ['vehiculos'] });
        }
    }, [flash?.success]);

    const handleDelete = (id: number) => {
        if (confirm('¿Estás seguro de que deseas eliminar este vehículo?')) {
            setDeleting(id);
            router.delete(`/vehiculos/${id}`, {
                onFinish: () => setDeleting(null),
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Vehículos" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Vehículos</h1>
                        <p className="text-muted-foreground text-sm">
                            Gestiona los vehículos registrados
                        </p>
                    </div>
                    <Link href="/vehiculos/create">
                        <Button>
                            <Plus className="h-4 w-4" />
                            Nuevo Vehículo
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {vehiculos.length === 0 ? (
                        <Card className="col-span-full">
                            <CardContent className="flex flex-col items-center justify-center py-12">
                                <Car className="h-12 w-12 text-muted-foreground mb-4" />
                                <p className="text-muted-foreground">
                                    No hay vehículos registrados
                                </p>
                                <Link href="/vehiculos/create" className="mt-4">
                                    <Button>Crear primer vehículo</Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ) : (
                        vehiculos.map((vehiculo) => (
                            <Card key={vehiculo.id}>
                                <CardHeader>
                                    <CardTitle className="flex items-center justify-between">
                                        <span>{vehiculo.modelo}</span>
                                        <div className="flex gap-2">
                                            <Link href={`/vehiculos/${vehiculo.id}/edit`}>
                                                <Button variant="ghost" size="icon">
                                                    <Edit className="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => handleDelete(vehiculo.id)}
                                                disabled={deleting === vehiculo.id}
                                            >
                                                <Trash2 className="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-2 text-sm">
                                        <p className="text-muted-foreground">
                                            <span className="font-medium">Marca:</span>{' '}
                                            {vehiculo.marca?.nombre_marca || 'N/A'}
                                        </p>
                                        <p className="text-muted-foreground">
                                            <span className="font-medium">Puertas:</span>{' '}
                                            {vehiculo.numero_puertas}
                                        </p>
                                        <p className="text-muted-foreground">
                                            <span className="font-medium">Color:</span>{' '}
                                            {vehiculo.color}
                                        </p>
                                    </div>
                                    <Link href={`/vehiculos/${vehiculo.id}`} className="mt-4 block">
                                        <Button variant="outline" className="w-full">
                                            Ver detalles
                                        </Button>
                                    </Link>
                                </CardContent>
                            </Card>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}

