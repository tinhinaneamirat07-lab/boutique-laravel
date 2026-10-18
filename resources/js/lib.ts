// Petits outils partagés par toutes les pages
import { usePage } from '@inertiajs/react';

export type Card = {
    id: number; name: string; subtitle: string | null; category: string;
    price: number; old_price: number | null; stock: number; is_new: boolean; image: string | null;
};

type Shared = { locale: 'ar' | 'fr'; translations: Record<string, string> };

// t('نص عربي') -> la traduction française si la langue est FR (même dictionnaire que __() en Laravel)
export function useT() {
    const { locale, translations } = usePage<Shared>().props;
    const t = (ar: string) => (locale === 'fr' ? translations[ar] ?? ar : ar);
    return { t, locale, fr: locale === 'fr' };
}

// 8900 -> "8 900" avec une espace insécable : le nombre reste dans le bon sens en arabe (RTL)
export const num = (n: number) => n.toLocaleString('fr-FR').replace(/\s/g, ' ');

export const CATS: Record<string, string> = {
    heels: 'كعب عالي', sneakers: 'سنيكرز', sport: 'رياضة', boots: 'بوتين', classic: 'موكاسان', sandals: 'صنادل',
};

export const discount = (p: Card) => (p.old_price ? Math.round((1 - p.price / p.old_price) * 100) : 0);
