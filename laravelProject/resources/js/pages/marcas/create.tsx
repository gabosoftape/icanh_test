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
        title: 'Marcas',
        href: '/marcas',
    },
    {
        title: 'Crear',
        href: '/marcas/create',
    },
];

export default function MarcasCreate() {
    const { flash } = usePage<SharedData>().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        nombre_marca: '',
        pais: '',
    });

    // Limpiar el formulario cuando aparece el mensaje de éxito
    useEffect(() => {
        if (flash?.success) {
            reset();
        }
    }, [flash?.success, reset]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/marcas', {
            onSuccess: () => {
                // La redirección se maneja en el controlador
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Crear Marca" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/marcas">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold">Crear Nueva Marca</h1>
                        <p className="text-muted-foreground text-sm">
                            Ingresa los datos de la nueva marca de vehículo
                        </p>
                    </div>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información de la Marca</CardTitle>
                        <CardDescription>
                            Completa el formulario para crear una nueva marca
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="nombre_marca">
                                    Nombre de la Marca *
                                </Label>
                                <Input
                                    id="nombre_marca"
                                    value={data.nombre_marca}
                                    onChange={(e) =>
                                        setData('nombre_marca', e.target.value)
                                    }
                                    placeholder="Ej: Toyota, Ford, BMW"
                                    required
                                    aria-invalid={errors.nombre_marca ? 'true' : 'false'}
                                />
                                {errors.nombre_marca && (
                                    <p className="text-destructive text-sm">
                                        {errors.nombre_marca}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="pais">País *</Label>
                                <Input
                                    id="pais"
                                    value={data.pais}
                                    onChange={(e) => setData('pais', e.target.value)}
                                    placeholder="Ej: Japón, Estados Unidos, Alemania"
                                    required
                                    aria-invalid={errors.pais ? 'true' : 'false'}
                                />
                                {errors.pais && (
                                    <p className="text-destructive text-sm">
                                        {errors.pais}
                                    </p>
                                )}
                            </div>

                            <div className="flex gap-4 pt-4">
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Creando...' : 'Crear Marca'}
                                </Button>
                                <Link href="/marcas">
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

