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
import { Plus, Edit, Trash2, Users } from 'lucide-react';
import { useEffect, useState } from 'react';
import { type SharedData } from '@/types';

interface Persona {
    id: number;
    nombre: string;
    cedula: string;
}

interface Props {
    personas: Persona[];
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Personas',
        href: '/personas',
    },
];

export default function PersonasIndex({ personas: initialPersonas }: Props) {
    const [deleting, setDeleting] = useState<number | null>(null);
    const { flash } = usePage<SharedData>().props;
    const [personas, setPersonas] = useState(initialPersonas);

    // Actualizar personas cuando cambian los props
    useEffect(() => {
        setPersonas(initialPersonas);
    }, [initialPersonas]);

    // Recargar datos cuando hay un mensaje flash de éxito
    useEffect(() => {
        if (flash?.success) {
            router.reload({ only: ['personas'] });
        }
    }, [flash?.success]);

    const handleDelete = (id: number) => {
        if (confirm('¿Estás seguro de que deseas eliminar esta persona?')) {
            setDeleting(id);
            router.delete(`/personas/${id}`, {
                onFinish: () => setDeleting(null),
            });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Personas" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Personas</h1>
                        <p className="text-muted-foreground text-sm">
                            Gestiona las personas registradas
                        </p>
                    </div>
                    <Link href="/personas/create">
                        <Button>
                            <Plus className="h-4 w-4" />
                            Nueva Persona
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {personas.length === 0 ? (
                        <Card className="col-span-full">
                            <CardContent className="flex flex-col items-center justify-center py-12">
                                <Users className="h-12 w-12 text-muted-foreground mb-4" />
                                <p className="text-muted-foreground">
                                    No hay personas registradas
                                </p>
                                <Link href="/personas/create" className="mt-4">
                                    <Button>Crear primera persona</Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ) : (
                        personas.map((persona) => (
                            <Card key={persona.id}>
                                <CardHeader>
                                    <CardTitle className="flex items-center justify-between">
                                        <span>{persona.nombre}</span>
                                        <div className="flex gap-2">
                                            <Link href={`/personas/${persona.id}/edit`}>
                                                <Button variant="ghost" size="icon">
                                                    <Edit className="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button
                                                variant="ghost"
                                                size="icon"
                                                onClick={() => handleDelete(persona.id)}
                                                disabled={deleting === persona.id}
                                            >
                                                <Trash2 className="h-4 w-4 text-destructive" />
                                            </Button>
                                        </div>
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-muted-foreground text-sm">
                                        Cédula: {persona.cedula}
                                    </p>
                                    <Link href={`/personas/${persona.id}`} className="mt-4 block">
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

