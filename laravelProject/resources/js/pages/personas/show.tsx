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

interface Persona {
    id: number;
    nombre: string;
    cedula: string;
}

interface Props {
    persona: Persona;
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
    {
        title: persona?.nombre || 'Detalles',
    },
];

export default function PersonasShow({ persona }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Persona - ${persona.nombre}`} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center gap-4">
                    <Link href="/personas">
                        <Button variant="ghost" size="icon">
                            <ArrowLeft className="h-4 w-4" />
                        </Button>
                    </Link>
                    <div className="flex-1">
                        <h1 className="text-2xl font-semibold">{persona.nombre}</h1>
                        <p className="text-muted-foreground text-sm">
                            Detalles de la persona
                        </p>
                    </div>
                    <Link href={`/personas/${persona.id}/edit`}>
                        <Button>
                            <Edit className="h-4 w-4 mr-2" />
                            Editar
                        </Button>
                    </Link>
                </div>

                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Información de la Persona</CardTitle>
                        <CardDescription>
                            Detalles completos de la persona
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div>
                            <Label className="text-muted-foreground text-sm">Nombre</Label>
                            <p className="text-lg font-medium">{persona.nombre}</p>
                        </div>
                        <div>
                            <Label className="text-muted-foreground text-sm">Cédula</Label>
                            <p className="text-lg font-medium">{persona.cedula}</p>
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

