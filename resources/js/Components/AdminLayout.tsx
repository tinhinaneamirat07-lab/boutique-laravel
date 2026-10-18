import { Head, Link, router, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import Icon from './Icon';
import Logo from './Logo';
import { useT } from '../lib';

type Shared = { flash: { success?: string }; auth: { name?: string } };

// Menu latéral : [url, libellé, icône]
const MENU: [string, string, string][] = [
    ['/admin/commandes', 'الطلبات', 'list'],
    ['/admin/produits', 'المنتوجات', 'shoe'],
    ['/admin/produits/create', 'منتوج جديد', 'plus'],
    ['/admin/livraison', 'التوصيل', 'pin'],
    ['/admin/statistiques', 'الإحصائيات', 'chart'],
];

export default function AdminLayout({ title, children }: { title: string; children: ReactNode }) {
    const { t, locale } = useT();
    const { url, props } = usePage<Shared>();
    const path = url.split('?')[0];

    return (
        <div className="adm">
            <Head title={`${t(title)} · Admin`} />
            <aside className="side dk">
                <Link href="/"><Logo size={40} /></Link>
                {MENU.map(([href, label, icon]) => (
                    <Link key={href} href={href} className={path === href ? 'on' : ''}><Icon name={icon} />{t(label)}</Link>
                ))}
                <div className="help">
                    <b>{t('نصيحة')}</b>{t('عيطي للطلبات الجديدة في أقل من ساعتين باش ترفعي نسبة التأكيد.')}
                    <div className="lang" style={{ marginTop: 12, background: 'rgba(245,239,198,.1)' }}>
                        <a href="/langue/ar" className={locale === 'ar' ? 'on' : ''} style={{ color: locale === 'ar' ? 'var(--java)' : undefined }}>AR</a>
                        <a href="/langue/fr" className={locale === 'fr' ? 'on' : ''} style={{ color: locale === 'fr' ? 'var(--java)' : undefined }}>FR</a>
                    </div>
                </div>
                <button onClick={() => router.post('/admin/logout')} style={{ display: 'flex', gap: 12, padding: '12px 14px', opacity: .7 }}>
                    <Icon name="out" />{t('خروج')}
                </button>
            </aside>
            <main className="am">
                {props.flash.success && <div className="flash">{props.flash.success}</div>}
                {children}
            </main>
        </div>
    );
}
