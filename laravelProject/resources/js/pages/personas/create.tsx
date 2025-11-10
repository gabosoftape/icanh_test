import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { useEffect } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Personas',
        href: '/personas',
    },
    {
        title: 'Crear',
        href: '/personas/create',
    },
];

export default function PersonasCreate() {
    const { flash } = usePage<SharedData>().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre: '',
        cedula: '',
    });

    // Limpiar el formulario cuando aparece el mensaje de éxito
    useEffect(() => {
        if (flash?.success) {
            reset();
        }
    }, [flash?.success, reset]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/personas');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Persona" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/personas">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold">Crear Nueva Persona</h1>
                        <p className="text-muted-foreground text-sm">
                            Ingresa los datos de la nueva persona
                        </p>
                    </div>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información de la Persona</CardTitle>
                        <CardDescription>
                            Completa el formulario para crear una nueva persona
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="nombre">Nombre *</Label>
                                <Input
                                    id="nombre"
                                    value={data.nombre}
                                    onChange={(e) => setData('nombre', e.target.value)}
                                    placeholder="Ej: Juan Pérez"
                                    required
                                    aria-invalid={errors.nombre ? 'true' : 'false'}
                                />
                                {errors.nombre && (
                                    <p className="text-destructive text-sm">
                                        {errors.nombre}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="cedula">Cédula *</Label>
                                <Input
                                    id="cedula"
                                    value={data.cedula}
                                    onChange={(e) => setData('cedula', e.target.value)}
                                    placeholder="Ej: 1234567890"
                                    required
                                    aria-invalid={errors.cedula ? 'true' : 'false'}
                                />
                                {errors.cedula && (
                                    <p className="text-destructive text-sm">
                                        {errors.cedula}
                                    </p>
                                )}
                            </div>

                            <div className="flex gap-4 pt-4">
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Creando...' : 'Crear Persona'}
                                </Button>
                                <Link href="/personas">
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

