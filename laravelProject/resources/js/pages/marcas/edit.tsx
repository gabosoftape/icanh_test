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
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';

interface Marca {
    id: number;
    nombre_marca: string;
    pais: string;
}

interface Props {
    marca: Marca;
}

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
        title: 'Editar',
    },
];

export default function MarcasEdit({ marca }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        nombre_marca: marca.nombre_marca,
        pais: marca.pais,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/marcas/${marca.id}`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Editar Marca - ${marca.nombre_marca}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/marcas">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 className="text-2xl font-semibold">Editar Marca</h1>
                        <p className="text-muted-foreground text-sm">
                            Modifica los datos de la marca
                        </p>
                    </div>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información de la Marca</CardTitle>
                        <CardDescription>
                            Actualiza los datos de la marca
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
                                    {processing ? 'Guardando...' : 'Guardar Cambios'}
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

