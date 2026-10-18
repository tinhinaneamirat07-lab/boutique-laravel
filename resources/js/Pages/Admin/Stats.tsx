import AdminLayout from '../../Components/AdminLayout';
import { CATS, num, useT } from '../../lib';

type Props = {
    kpis: { revenue: number; orders: number; basket: number; pairs: number };
    weeks: { label: string; value: number }[];
    byCategory: Record<string, number>;
    topProducts: { name: string; image: string | null; qty: number }[];
    topWilayas: { id: number; name: string; n: number }[];
    rates: { confirmed: number; cancelled: number };
};
const COLORS = ['var(--sceptre)', 'var(--sky)', 'var(--soil)', 'var(--java)', '#D8C98A', '#B9A7A0'];

export default function Stats({ kpis, weeks, byCategory, topProducts, topWilayas, rates }: Props) {
    const { t } = useT();
    const max = Math.max(1, ...weeks.map((w) => w.value));
    const cats = Object.entries(byCategory).map(([k, v]) => [k, Number(v)] as const).sort((a, b) => b[1] - a[1]);
    const pairs = cats.reduce((a, [, v]) => a + v, 0);
    // donut en conic-gradient : chaque catégorie prend sa part du cercle
    let acc = 0;
    const donut = cats.map(([, v], i) => { const from = acc; acc += pairs ? (v / pairs) * 100 : 0; return `${COLORS[i % 6]} ${from}% ${acc}%`; }).join(',');
    const topMax = Math.max(1, ...topProducts.map((p) => p.qty));
    const wMax = Math.max(1, ...topWilayas.map((w) => w.n));

    return (
        <AdminLayout title="الإحصائيات">
            <div className="atop"><div><h1>{t('الإحصائيات')}</h1><p>{t('هذا الشهر')}</p></div></div>
            <div className="kp">
                <div className="k hl"><span>{t('مداخيل مُسلّمة')}</span><b>{num(kpis.revenue)}</b><small style={{ background: 'rgba(245,239,198,.15)', color: 'var(--cream)' }}>{t('دج')}</small></div>
                <div className="k"><span>{t('طلبات')}</span><b>{kpis.orders}</b></div>
                <div className="k"><span>{t('متوسط الطلب')}</span><b>{num(kpis.basket)}</b><small>{t('دج')}</small></div>
                <div className="k"><span>{t('أزواج مباعة')}</span><b>{kpis.pairs}</b></div>
            </div>
            <div className="cols3">
                <div className="pn"><div className="h"><h2>{t('المداخيل حسب الأسبوع')}</h2><span style={{ fontSize: 13, color: 'var(--soil)' }}>{t('دج')}</span></div>
                    <div className="chartbox">
                        <div className="colchart">{weeks.map((w) => (
                            <div key={w.label} className={w.value === max ? 'top' : ''}><b>{w.value ? num(Math.round(w.value / 1000)) + 'K' : '0'}</b><i style={{ height: `${Math.max(2, (w.value / max) * 85)}%` }} /></div>
                        ))}</div>
                        <div className="xl">{weeks.map((w) => <span key={w.label}>{w.label}</span>)}</div>
                    </div>
                </div>
                <div className="pn"><div className="h"><h2>{t('حسب الفئة')}</h2></div>
                    <div className="donut">
                        <div className="d" style={{ background: pairs ? `conic-gradient(${donut})` : 'var(--cream)' }}><div><span><b>{pairs}</b><br /><small style={{ fontSize: 12, color: 'var(--soil)' }}>{t('زوج')}</small></span></div></div>
                        <div className="legend">{cats.map(([k, v], i) => (
                            <div key={k}><i style={{ background: COLORS[i % 6] }} />{t(CATS[k])}<b>{pairs ? Math.round((v / pairs) * 100) : 0}%</b></div>
                        ))}</div>
                    </div>
                </div>
            </div>
            <div className="cols3">
                <div className="pn"><div className="h"><h2>{t('الأكثر مبيعاً')}</h2></div>
                    <div className="bars">{topProducts.map((p) => (
                        <div key={p.name} className="br">{p.image && <span className="t"><img src={p.image} alt="" /></span>}
                            <div className="w"><div><span>{p.name}</span><b>{p.qty}</b></div><div className="tr"><i style={{ width: `${(p.qty / topMax) * 100}%` }} /></div></div></div>
                    ))}{topProducts.length === 0 && <p style={{ color: 'var(--soil)' }}>{t('لا توجد بيانات بعد')}</p>}</div>
                </div>
                <div className="pn"><div className="h"><h2>{t('نسبة التأكيد')}</h2></div>
                    <div className="rate" style={{ gridTemplateColumns: '1fr 1fr' }}>
                        <div><b>{rates.confirmed}%</b><span>{t('مؤكدة')}</span></div>
                        <div><b>{rates.cancelled}%</b><span>{t('ملغى')}</span></div>
                    </div>
                    <div className="h" style={{ paddingTop: 0 }}><h2>{t('أكثر الولايات')}</h2></div>
                    <div className="bars map">{topWilayas.map((w) => (
                        <div key={w.id} className="br"><span className="num">{w.id}</span>
                            <div className="w"><div><span>{w.name}</span><b>{w.n}</b></div><div className="tr"><i style={{ width: `${(w.n / wMax) * 100}%` }} /></div></div></div>
                    ))}</div>
                </div>
            </div>
        </AdminLayout>
    );
}
