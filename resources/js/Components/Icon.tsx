// Icônes SVG (trait fin) : pas d'emoji dans l'interface
const paths: Record<string, string> = {
    bag: 'M5 8h14l-1 12H6L5 8z M9 8V6a3 3 0 0 1 6 0v2',
    heart: 'M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z',
    search: 'M11 5a6 6 0 1 1 0 12a6 6 0 0 1 0-12z M20 20l-4.5-4.5',
    arrow: 'M19 12H5 M11 6l-6 6 6 6',
    plus: 'M12 5v14 M5 12h14',
    truck: 'M3 7h11v9H3z M14 10h4l3 3v3h-7z M7 16a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3z M17 16a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3z',
    cash: 'M5 6h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z M12 9.5a2.5 2.5 0 1 1 0 5a2.5 2.5 0 0 1 0-5z',
    phone: 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z',
    zoom: 'M11 5a6 6 0 1 1 0 12a6 6 0 0 1 0-12z M20 20l-4.5-4.5 M11 8v6 M8 11h6',
};

export default function Icon({ name, size = 20 }: { name: keyof typeof paths | string; size?: number }) {
    return (
        <svg className="i" width={size} height={size} viewBox="0 0 24 24" aria-hidden="true">
            <path d={paths[name]} />
        </svg>
    );
}
