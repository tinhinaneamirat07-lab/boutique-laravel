import { useForm } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import AdminLayout from '../../Components/AdminLayout';
import { useT } from '../../lib';

type W = { id: number; ar: string; fr: string; home: number; desk: number; zone: string };
const ZONES: Record<string, string> = { centre: 'الوسط', est: 'الشرق', ouest: 'الغرب', sud: 'الجنوب' };
const ZB: Record<string, string> = { centre: 'c', est: 'n', ouest: 's', sud: 's' };

export default function Shipping({ wilayas }: { wilayas: W[] }) {
    const { t, fr } = useT();
    const [q, setQ] = useState('');
    const [zone, setZone] = useState('all');
    // un seul formulaire pour les 58 wilayas : prices[16] = { home, desk }
    const form = useForm({ prices: Object.fromEntries(wilayas.map((w) => [w.id, { home: w.home, desk: w.desk }])) as Record<number, { home: number; desk: number }> });
    const set = (id: number, key: 'home' | 'desk', v: number) => form.setData('prices', { ...form.data.prices, [id]: { ...form.data.prices[id], [key]: v } });

    const list = useMemo(() => wilayas.filter((w) =>
        (zone === 'all' || w.zone === zone) && `${w.id} ${w.ar} ${w.fr.toLowerCase()}`.includes(q.toLowerCase())), [wilayas, q, zone]);
    const avg = (k: 'home' | 'desk') => Math.round(Object.values(form.data.prices).reduce((a, p) => a + p[k], 0) / wilayas.length);

    // appliquer un même prix à toute la région affichée
    const applyZone = () => {
        const home = Number(prompt(t('للمنزل (دج)'))); const desk = Number(prompt(t('Stop desk (دج)')));
        if (!home || !desk) return;
        const p = { ...form.data.prices };
        list.forEach((w) => { p[w.id] = { home, desk }; });
        form.setData('prices', p);
    };

    return (
        <AdminLayout title="التوصيل">
            <div className="atop">
                <div><h1>{t('أسعار التوصيل')}</h1><p>{t('58 ولاية · للمنزل و Stop desk')}</p></div>
                <button className="abtn" disabled={form.processing} onClick={() => form.put('/admin/livraison', { preserveScroll: true })}>{t('حفظ التغييرات')}</button>
            </div>
            <div className="kp" style={{ gridTemplateColumns: 'repeat(3,1fr)' }}>
                <div className="k"><span>{t('متوسط للمنزل')}</span><b>{avg('home')} <small style={{ background: 'none', color: 'var(--soil)' }}>{t('دج')}</small></b></div>
                <div className="k"><span>{t('متوسط Stop desk')}</span><b>{avg('desk')} <small style={{ background: 'none', color: 'var(--soil)' }}>{t('دج')}</small></b></div>
                <div className="k" style={{ background: 'var(--sky)' }}><span style={{ color: 'var(--java)' }}>{t('الولايات')}</span><b>{wilayas.length}</b></div>
            </div>
            <div className="pn">
                <div className="h">
                    <div className="shiptools">
                        <input className="srch" style={{ width: 240, border: 0 }} placeholder={t('ابحثي عن ولاية…')} value={q} onChange={(e) => setQ(e.target.value)} />
                        <div className="tabs">
                            <button className={zone === 'all' ? 'on' : ''} onClick={() => setZone('all')}>{t('الكل')}</button>
                            {Object.entries(ZONES).map(([k, l]) => <button key={k} className={zone === k ? 'on' : ''} onClick={() => setZone(k)}>{t(l)}</button>)}
                        </div>
                    </div>
                    <button className="abtn gh" onClick={applyZone}>{t('تطبيق سعر على المنطقة')}</button>
                </div>
                <table className="ot">
                    <thead><tr><th>{t('الولاية')}</th><th>{t('المنطقة')}</th><th>{t('للمنزل (دج)')}</th><th>{t('Stop desk (دج)')}</th></tr></thead>
                    <tbody>
                        {list.map((w) => (
                            <tr key={w.id}>
                                <td><div className="cu"><span className="wn">{String(w.id).padStart(2, '0')}</span><b>{fr ? w.fr : w.ar}</b></div></td>
                                <td><span className={`zb ${ZB[w.zone]}`}>{t(ZONES[w.zone])}</span></td>
                                <td><input className="pin" type="number" min={0} value={form.data.prices[w.id].home} onChange={(e) => set(w.id, 'home', Number(e.target.value))} /></td>
                                <td><input className="pin" type="number" min={0} value={form.data.prices[w.id].desk} onChange={(e) => set(w.id, 'desk', Number(e.target.value))} /></td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
