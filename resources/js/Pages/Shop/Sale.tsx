import { useEffect, useState } from 'react';
import Layout from '../../Components/Layout';
import ProductCard from '../../Components/ProductCard';
import { useT, type Card } from '../../lib';

// Compte à rebours jusqu'à la fin de l'offre (fin du mois, pour la démo)
function useCountdown() {
    const end = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 1).getTime();
    const [left, setLeft] = useState(end - Date.now());
    useEffect(() => { const id = setInterval(() => setLeft(end - Date.now()), 1000); return () => clearInterval(id); }, [end]);
    const s = Math.max(0, Math.floor(left / 1000));
    const p = (n: number) => String(n).padStart(2, '0');
    return [p(Math.floor(s / 86400)), p(Math.floor(s / 3600) % 24), p(Math.floor(s / 60) % 60), p(s % 60)];
}

export default function Sale({ products }: { products: Card[] }) {
    const { t, fr } = useT();
    const [d, h, m, s] = useCountdown();
    const best = Math.max(0, ...products.map((p) => (p.old_price ? Math.round((1 - p.price / p.old_price) * 100) : 0)));

    return (
        <Layout title="Sale">
            <div className="wrap">
                <div className="salehero rv">
                    <div>
                        <div className="pct">-{best}%</div>
                        <h1>{fr ? <>Soldes d'automne<br />sur une sélection.</> : <>Sale d'automne<br />على أحذية مختارة.</>}</h1>
                        <p style={{ opacity: .8, marginTop: 10 }}>{t('الكميات محدودة. العرض ينتهي في:')}</p>
                        <div className="cd">
                            {[[d, 'أيام'], [h, 'ساعة'], [m, 'دقيقة'], [s, 'ثانية']].map(([v, l]) => <div key={l}><b>{v}</b><span>{t(l)}</span></div>)}
                        </div>
                    </div>
                    <div className="imgs">
                        <div className="p p1"><img src="/maquettes/images/27256450.jpg" alt="" /></div>
                        <div className="p p2"><img src="/maquettes/images/5488660.jpg" alt="" /></div>
                    </div>
                </div>
                <section className="sec" style={{ paddingTop: 44 }}>
                    <div className="hd">{fr ? <h2>Toutes <span className="it">les</span> promos</h2> : <h2>كل <span className="it">les</span> تخفيضات</h2>}</div>
                    <div className="grid">{products.map((p) => <ProductCard key={p.id} p={p} />)}</div>
                </section>
            </div>
        </Layout>
    );
}
