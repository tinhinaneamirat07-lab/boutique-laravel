import { router } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import Layout from '../../Components/Layout';
import ProductCard from '../../Components/ProductCard';
import { CATS, useT, type Card } from '../../lib';

export default function Catalogue({ products, cat }: { products: Card[]; cat: string }) {
    const { t, fr } = useT();
    const [sort, setSort] = useState('new');
    const [onlySale, setOnlySale] = useState(false);
    const [inStock, setInStock] = useState(false);

    // filtres et tri côté navigateur (la catégorie, elle, est filtrée par Laravel)
    const list = useMemo(() => {
        let l = products.filter((p) => (!onlySale || p.old_price) && (!inStock || p.stock > 0));
        if (sort === 'asc') l = [...l].sort((a, b) => a.price - b.price);
        if (sort === 'desc') l = [...l].sort((a, b) => b.price - a.price);
        return l;
    }, [products, sort, onlySale, inStock]);

    const go = (c: string) => router.get('/catalogue', c === 'all' ? {} : { cat: c }, { preserveScroll: true });

    return (
        <Layout title={cat === 'all' ? 'Catalogue' : t(CATS[cat])}>
            <div className="wrap">
                <div className="lhead">
                    <div>
                        <h1>{cat !== 'all' ? t(CATS[cat]) : fr ? <>Toutes <span className="it">les</span> chaussures</> : <>كل <span className="it">les</span> أحذية</>}</h1>
                        <p>{t('توصيل لـ 58 ولاية · الدفع عند الاستلام')}</p>
                    </div>
                </div>
                <div className="chips">
                    <button className={cat === 'all' ? 'on' : ''} onClick={() => go('all')}>{t('الكل')}</button>
                    {Object.entries(CATS).map(([k, n]) => (
                        <button key={k} className={cat === k ? 'on' : ''} onClick={() => go(k)}>{t(n)}</button>
                    ))}
                </div>
                <div className="lay" style={{ marginTop: 18 }}>
                    <aside className="filters">
                        <div><h4>{t('العروض')}</h4>
                            <div className="chk">
                                <label><input type="checkbox" checked={onlySale} onChange={(e) => setOnlySale(e.target.checked)} />{t('في التخفيض فقط')}</label>
                                <label><input type="checkbox" checked={inStock} onChange={(e) => setInStock(e.target.checked)} />{t('متوفر فقط')}</label>
                            </div>
                        </div>
                        <div><h4>{t('المقاس')}</h4>
                            <div className="szs">{[35, 36, 37, 38, 39, 40, 41, 42].map((s) => <button key={s} type="button">{s}</button>)}</div>
                        </div>
                    </aside>
                    <div>
                        <div className="toolbar">
                            <span className="count">{list.length} {t('موديل')}</span>
                            <select className="sel" value={sort} onChange={(e) => setSort(e.target.value)}>
                                <option value="new">{t('الأحدث')}</option>
                                <option value="asc">{t('السعر: من الأقل')}</option>
                                <option value="desc">{t('السعر: من الأعلى')}</option>
                            </select>
                        </div>
                        <div className="grid3">{list.map((p) => <ProductCard key={p.id} p={p} />)}</div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
