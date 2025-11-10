import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { router, usePage } from '@inertiajs/react';
import { CheckCircle2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import { type SharedData } from '@/types';

export default function FlashMessage() {
    const { flash } = usePage<SharedData>().props;
    const [open, setOpen] = useState(false);
    const [message, setMessage] = useState<string | null>(null);
    const [hasShown, setHasShown] = useState(false);

    useEffect(() => {
        if (flash?.success && !hasShown) {
            setMessage(flash.success);
            setOpen(true);
            setHasShown(true);
        } else if (!flash?.success && !flash?.error) {
            // Reset cuando no hay mensajes flash
            setHasShown(false);
            setMessage(null);
            setOpen(false);
        }
    }, [flash, hasShown]);

    const handleClose = () => {
        setOpen(false);
        
        // Determinar a qué lista redirigir basado en la URL actual
        const currentPath = window.location.pathname;
        if (currentPath.includes('/marcas/create')) {
            router.visit('/marcas');
        } else if (currentPath.includes('/personas/create')) {
            router.visit('/personas');
        } else if (currentPath.includes('/vehiculos/create')) {
            router.visit('/vehiculos');
        }
        
        // Limpiar el mensaje después de cerrar
        setTimeout(() => {
            setMessage(null);
            setHasShown(false);
        }, 200);
    };

    if (!message || !open) {
        return null;
    }

    return (
        <Dialog open={open} onOpenChange={handleClose}>
            <DialogContent className="sm:max-w-sm">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2 text-lg">
                        <CheckCircle2 className="h-5 w-5 text-green-600 dark:text-green-400" />
                        Creación exitosa
                    </DialogTitle>
                    <DialogDescription className="text-base pt-2">
                        {message}
                    </DialogDescription>
                </DialogHeader>
                <div className="flex justify-end mt-4">
                    <Button onClick={handleClose} variant="default">
                        Cerrar
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
    );
}

