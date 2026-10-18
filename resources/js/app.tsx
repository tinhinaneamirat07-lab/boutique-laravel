// Point d'entrée React : Inertia charge la page demandée par Laravel (Inertia::render)
import '../css/shop.css';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

createInertiaApp({
    title: (title) => (title ? `${title} · Miza` : 'Miza'),
    // 'Shop/Home' -> ./Pages/Shop/Home.tsx
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true });
        return pages[`./Pages/${name}.tsx`] as never;
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
    progress: { color: '#4D0E12' },
});
