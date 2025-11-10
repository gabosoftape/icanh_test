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
        title: marca?.nombre_marca || 'Detalles',
    },
];

export default function MarcasShow({ marca }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Marca - ${marca.nombre_marca}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/marcas">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div className="flex-1">
                        <h1 className="text-2xl font-semibold">{marca.nombre_marca}</h1>
                        <p className="text-muted-foreground text-sm">
                            Detalles de la marca
                        </p>
                    </div>
                    <Link href={`/marcas/${marca.id}/edit`}>
                        <Button>
                            <Edit className="h-4 w-4 mr-2" />
                            Editar
                        </Button>
                    </Link>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información de la Marca</CardTitle>
                        <CardDescription>
                            Detalles completos de la marca de vehículo
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <Label className="text-muted-foreground text-sm">
                                Nombre de la Marca
                            </Label>
                            <p className="text-lg font-medium">{marca.nombre_marca}</p>
                        </div>
                        <div>
                            <Label className="text-muted-foreground text-sm">País</Label>
                            <p className="text-lg font-medium">{marca.pais}</p>
                        </div>
                    </CardContent>
                </Card>
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

