import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../../Components/AdminLayout';
import Icon from '../../Components/Icon';
import { CATS, num, useT } from '../../lib';

type P = { id: number; name: string; subtitle: string | null; category: string; price: number; old_price: number | null;
    stock: number; sizes: Record<string, number>; image: string | null; is_active: boolean; orders: number };

export default function Products({ products }: { products: P[] }) {
    const { t } = useT();
    const [cat, setCat] = useState('all');
    const list = cat === 'all' ? products : products.filter((p) => p.category === cat);
    const low = products.filter((p) => p.stock > 0 && p.stock < 5).length;

    const remove = (p: P) => { if (confirm(t('حذف هذا المنتوج؟'))) router.delete(`/admin/produits/${p.id}`, { preserveScroll: true }); };

    return (
        <AdminLayout title="المنتوجات">
            <div className="atop">
                <div><h1>{t('المنتوجات')}</h1><p>{products.length} {t('موديل')} · {low} {t('قريب يخلصو')}</p></div>
                <Link href="/admin/produits/create" className="abtn"><Icon name="plus" />{t('منتوج جديد')}</Link>
            </div>
            <div className="pn">
                <div className="h"><div className="tabs">
                    <button className={cat === 'all' ? 'on' : ''} onClick={() => setCat('all')}>{t('الكل')}</button>
                    {Object.entries(CATS).map(([k, n]) => <button key={k} className={cat === k ? 'on' : ''} onClick={() => setCat(k)}>{t(n)}</button>)}
                </div></div>
                <table className="ot">
                    <thead><tr><th>{t('المنتوج')}</th><th>{t('الفئة')}</th><th>{t('السعر')}</th><th>{t('المقاسات')}</th><th>{t('المخزون')}</th><th>{t('ظاهر')}</th><th /></tr></thead>
                    <tbody>
                        {list.map((p) => (
                            <tr key={p.id} style={{ opacity: p.is_active ? 1 : .5 }}>
                                <td><div className="pd">{p.image && <span className="t"><img src={p.image} alt="" /></span>}
                                    <div><b style={{ fontWeight: 500 }}>{p.name}</b><br /><small style={{ color: 'var(--soil)' }}>{p.subtitle}</small></div></div></td>
                                <td>{t(CATS[p.category])}</td>
                                <td style={{ fontFamily: 'Outfit', fontWeight: 600 }}>{num(p.price)} {t('دج')}{p.old_price && <><br /><span className="old" style={{ margin: 0 }}>{num(p.old_price)}</span></>}</td>
                                <td><div className="szmini">{Object.entries(p.sizes).map(([s, q]) => <span key={s} className={q ? '' : 'z'} title={`${q}`}>{s}</span>)}</div></td>
                                <td><span className={`stk ${p.stock < 5 ? 'low' : ''}`}>{p.stock === 0 ? t('نفذ') : p.stock}</span></td>
                                <td><button className={`sw ${p.is_active ? 'on' : ''}`} aria-label={t('ظاهر')}
                                    onClick={() => router.patch(`/admin/produits/${p.id}/visibilite`, {}, { preserveScroll: true })} /></td>
                                <td><div className="act">
                                    <Link href={`/admin/produits/${p.id}/edit`} aria-label={t('تعديل')}><Icon name="edit" size={16} /></Link>
                                    <a href="#" onClick={(e) => { e.preventDefault(); remove(p); }} aria-label={t('حذف')}><Icon name="trash" size={16} /></a>
                                </div></td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
