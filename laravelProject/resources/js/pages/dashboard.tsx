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
import { Car, Tag, Users, User as UserIcon } from 'lucide-react';

interface DashboardProps {
    counts: {
        marcas: number;
        personas: number;
        vehiculos: number;
        usuarios: number;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard({ counts }: DashboardProps) {
    const stats = [
        {
            title: 'Marcas',
            value: counts.marcas,
            description: 'Total de marcas registradas',
            icon: Tag,
            href: '/marcas',
            color: 'text-blue-600 dark:text-blue-400',
            bgColor: 'bg-blue-50 dark:bg-blue-950/20',
        },
        {
            title: 'Personas',
            value: counts.personas,
            description: 'Total de personas registradas',
            icon: Users,
            href: '/personas',
            color: 'text-green-600 dark:text-green-400',
            bgColor: 'bg-green-50 dark:bg-green-950/20',
        },
        {
            title: 'Vehículos',
            value: counts.vehiculos,
            description: 'Total de vehículos registrados',
            icon: Car,
            href: '/vehiculos',
            color: 'text-purple-600 dark:text-purple-400',
            bgColor: 'bg-purple-50 dark:bg-purple-950/20',
        },
        {
            title: 'Usuarios',
            value: counts.usuarios,
            description: 'Total de usuarios del sistema',
            icon: UserIcon,
            href: '#',
            color: 'text-orange-600 dark:text-orange-400',
            bgColor: 'bg-orange-50 dark:bg-orange-950/20',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-semibold">Dashboard</h1>
                    <p className="text-muted-foreground text-sm">
                        Resumen general del sistema
                    </p>
                </div>
                <div className="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {stats.map((stat) => {
                        const Icon = stat.icon;
                        const CardWrapper = stat.href !== '#' ? Link : 'div';
                        const wrapperProps =
                            stat.href !== '#'
                                ? { href: stat.href, className: 'block' }
                                : { className: 'block' };

                        return (
                            <CardWrapper key={stat.title} {...wrapperProps}>
                                <Card className="transition-colors hover:bg-accent/50">
                                    <CardHeader>
                                        <div className="flex items-center justify-between">
                                            <CardTitle className="text-lg">
                                                {stat.title}
                                            </CardTitle>
                                            <div
                                                className={`rounded-lg p-2 ${stat.bgColor}`}
                                            >
                                                <Icon
                                                    className={`h-5 w-5 ${stat.color}`}
                                                />
                                            </div>
                                        </div>
                                        <CardDescription>
                                            {stat.description}
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="text-3xl font-bold">
                                            {stat.value}
                                        </div>
                                    </CardContent>
                                </Card>
                            </CardWrapper>
                        );
                    })}
                </div>
            </div>
        </AppLayout>
    );
}
