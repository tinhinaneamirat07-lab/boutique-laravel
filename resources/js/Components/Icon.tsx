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
    list: 'M8 6h12 M8 12h12 M8 18h12 M4 6h.01 M4 12h.01 M4 18h.01',
    shoe: 'M3 17c4 0 6-6 9-6 2 0 3 2 6 3 2 .7 3 1.5 3 3H3z M5 17v3',
    pin: 'M12 21s-6-5.3-6-11a6 6 0 0 1 12 0c0 5.7-6 11-6 11z M12 8a2 2 0 1 1 0 4a2 2 0 0 1 0-4z',
    chart: 'M4 20V10 M10 20V4 M16 20v-7 M22 20H2',
    out: 'M15 4h4v16h-4 M10 8l-4 4 4 4 M6 12h10',
    edit: 'M4 20h4L19 9l-4-4L4 16v4z',
    trash: 'M5 7h14 M10 7V5h4v2 M7 7l1 13h8l1-13',
    zoom: 'M11 5a6 6 0 1 1 0 12a6 6 0 0 1 0-12z M20 20l-4.5-4.5 M11 8v6 M8 11h6',
};

export default function Icon({ name, size = 20 }: { name: keyof typeof paths | string; size?: number }) {
    return (
        <svg className="i" width={size} height={size} viewBox="0 0 24 24" aria-hidden="true">
            <path d={paths[name]} />
        </svg>
    );
}
