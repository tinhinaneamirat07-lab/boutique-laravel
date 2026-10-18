import { Link, router } from '@inertiajs/react';
import AdminLayout from '../../Components/AdminLayout';
import Icon from '../../Components/Icon';
import { num, useT } from '../../lib';

export const STATUS: Record<string, [string, string]> = {
    new: ['جديد', 'new'], confirmed: ['مؤكد', 'conf'], shipped: ['مُرسل', 'ship'], delivered: ['تم التسليم', 'done'], cancelled: ['ملغى', 'cancel'],
};

type Order = { id: number; name: string; phone: string; product: string; image: string | null; size: string | null; quantity: number;
    wilaya: string; commune: string; type: string; total: number; status: string; date: string };
type Page = { data: Order[]; links: { url: string | null; label: string; active: boolean }[]; total: number };
type Props = { orders: Page; filters: { status?: string; wilaya?: string }; wilayas: { id: number; name: string }[];
    kpis: { pending: number; today: number; month: number; revenue: number } };

export default function Orders({ orders, filters, wilayas, kpis }: Props) {
    const { t } = useT();
    const filter = (key: string, value: string) =>
        router.get('/admin/commandes', { ...filters, [key]: value || undefined }, { preserveState: true, replace: true });
    // changer le statut : PATCH, la page se met à jour sans rechargement
    const setStatus = (id: number, status: string) =>
        router.patch(`/admin/commandes/${id}/statut`, { status }, { preserveScroll: true });

    return (
        <AdminLayout title="الطلبات">
            <div className="atop"><div><h1>{t('الطلبات')}</h1><p>{orders.total} {t('طلب')}</p></div></div>
            <div className="kp">
                <div className="k hl"><span>{t('في انتظار التأكيد')}</span><b>{kpis.pending}</b>
                    <button className="go" onClick={() => filter('status', 'new')}><Icon name="phone" size={15} />{t('ابدئي الاتصالات')}</button></div>
                <div className="k"><span>{t('طلبات اليوم')}</span><b>{kpis.today}</b></div>
                <div className="k"><span>{t('طلبات الشهر')}</span><b>{kpis.month}</b></div>
                <div className="k"><span>{t('مداخيل الشهر')}</span><b>{num(kpis.revenue)}</b><small>{t('دج · مُسلّمة')}</small></div>
            </div>
            <div className="pn">
                <div className="h">
                    <div className="tabs">
                        <button className={!filters.status ? 'on' : ''} onClick={() => filter('status', '')}>{t('الكل')}</button>
                        {Object.entries(STATUS).map(([k, [l]]) => (
                            <button key={k} className={filters.status === k ? 'on' : ''} onClick={() => filter('status', k)}>{t(l)}</button>
                        ))}
                    </div>
                    <select className="chip" value={filters.wilaya ?? ''} onChange={(e) => filter('wilaya', e.target.value)}>
                        <option value="">{t('كل الولايات')}</option>
                        {wilayas.map((w) => <option key={w.id} value={w.id}>{w.id} - {w.name}</option>)}
                    </select>
                </div>
                <table className="ot">
                    <thead><tr><th>#</th><th>{t('الزبونة')}</th><th>{t('المنتوج')}</th><th>{t('الولاية')}</th><th>{t('المجموع')}</th><th>{t('الحالة')}</th><th /></tr></thead>
                    <tbody>
                        {orders.data.map((o) => (
                            <tr key={o.id}>
                                <td style={{ fontFamily: 'Outfit', color: 'var(--soil)' }}>#{o.id}<br /><small>{o.date}</small></td>
                                <td><div className="cu"><span className="ini" style={{ background: 'var(--cream)' }}>{o.name[0]}</span>
                                    <div><b>{o.name}</b><span>{o.phone}</span></div></div></td>
                                <td><div className="pd">{o.image && <span className="t"><img src={o.image} alt="" /></span>}
                                    <span>{o.product}<br /><small>{t('مقاس')} {o.size ?? '—'} × {o.quantity}</small></span></div></td>
                                <td>{o.wilaya}<br /><small style={{ color: 'var(--soil)' }}>{o.commune} · {o.type === 'home' ? t('للمنزل') : 'Stop desk'}</small></td>
                                <td style={{ fontFamily: 'Outfit', fontWeight: 600, whiteSpace: 'nowrap' }}>{num(o.total)} {t('دج')}</td>
                                <td>
                                    <select className={`stsel s ${STATUS[o.status][1]}`} value={o.status} onChange={(e) => setStatus(o.id, e.target.value)}>
                                        {Object.entries(STATUS).map(([k, [l]]) => <option key={k} value={k}>{t(l)}</option>)}
                                    </select>
                                </td>
                                <td><a href={`tel:${o.phone}`} className="callb" aria-label={t('اتصال')}><Icon name="phone" size={17} /></a></td>
                            </tr>
                        ))}
                        {orders.data.length === 0 && <tr><td colSpan={7} style={{ textAlign: 'center', padding: 40, color: 'var(--soil)' }}>{t('لا توجد طلبات')}</td></tr>}
                    </tbody>
                </table>
                {orders.links.length > 3 && (
                    <div className="pager">
                        {orders.links.map((l, i) => l.url
                            ? <Link key={i} href={l.url} className={l.active ? 'on' : ''} preserveScroll dangerouslySetInnerHTML={{ __html: l.label }} />
                            : <span key={i} dangerouslySetInnerHTML={{ __html: l.label }} />)}
                    </div>
                )}
            </div>
        </AdminLayout>
    );
}
