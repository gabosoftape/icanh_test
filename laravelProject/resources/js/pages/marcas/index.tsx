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
import { Head, Link, router, usePage } from '@inertiajs/react';
import { Plus, Edit, Trash2, Car } from 'lucide-react';
import { useEffect, useState } from 'react';
import { type SharedData } from '@/types';

interface Marca {
    id: number;
    nombre_marca: string;
    pais: string;
}

interface Props {
    marcas: Marca[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Marcas',
        href: '/marcas',
    }
];

export default function MarcasIndex({ marcas: initialMarcas }: Props) {
    const [deleting, setDeleting] = useState<number | null>(null);
    const { flash } = usePage<SharedData>().props;
    const [marcas, setMarcas] = useState(initialMarcas);

    // Actualizar marcas cuando cambian los props (después de crear/actualizar)
    useEffect(() => {
        setMarcas(initialMarcas);
    }, [initialMarcas]);

    // Recargar datos cuando hay un mensaje flash de éxito
    useEffect(() => {
        if (flash?.success) {
            router.reload({ only: ['marcas'] });
        }
    }, [flash?.success]);

    const handleDelete = (id: number) => {
        if (confirm('¿Estás seguro de que deseas eliminar esta marca?')) {
            setDeleting(id);
            router.delete(`/marcas/${id}`, {
                onFinish: () => setDeleting(null),
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Marcas" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Marcas de Vehículos</h1>
                        <p className="text-muted-foreground text-sm">
                            Gestiona las marcas de vehículos disponibles
                        </p>
                    </div>
                    <Link href="/marcas/create">
                        <Button>
                            <Plus className="h-4 w-4" />
                            Nueva Marca
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {marcas.length === 0 ? (
                        <Card className="col-span-full">
                            <CardContent className="flex flex-col items-center justify-center py-12">
                                <Car className="h-12 w-12 text-muted-foreground mb-4" />
                                <p className="text-muted-foreground">
                                    No hay marcas registradas
                                </p>
                                <Link href="/marcas/create" className="mt-4">
                                    <Button>Crear primera marca</Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ) : (
                        marcas.map((marca) => (
                            <Card key={marca.id}>
                                <CardHeader>
                                    <CardTitle className="flex items-center justify-between">
                                        <span>{marca.nombre_marca}</span>
                                        <div className="flex gap-2">
                                            <Link href={`/marcas/${marca.id}/edit`}>
                                                <Button variant="ghost" size="icon">
                                                    <Edit className="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => handleDelete(marca.id)}
                                                disabled={deleting === marca.id}
                                            >
                                                <Trash2 className="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </CardTitle>
                                    <CardDescription>País: {marca.pais}</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <Link href={`/marcas/${marca.id}`}>
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

