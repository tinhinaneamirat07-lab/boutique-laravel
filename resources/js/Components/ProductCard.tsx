import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Icon from './Icon';
import { CATS, discount, num, useT, type Card } from '../lib';

export default function ProductCard({ p }: { p: Card }) {
    const { t } = useT();
    const [fav, setFav] = useState(false);   // favori : simple état local (démo)
    const out = p.stock === 0;
    const pct = discount(p);

    return (
        <article className={`card ${out ? 'sold' : ''}`}>
            <div className="ph">
                {p.image && <img src={p.image} alt={p.name} loading="lazy" />}
                {out ? <span className="tag out">{t('نفذ المخزون')}</span>
                    : p.is_new ? <span className="tag">{t('جديد')}</span> : null}
                {pct > 0 && <span className="off">-{pct}%</span>}
                {!out && (
                    <>
                        <button className={`heart ${fav ? 'on' : ''}`} onClick={() => setFav(!fav)} aria-label={t('المفضلة')}>
                            <Icon name="heart" />
                        </button>
                        <Link href={`/produits/${p.id}`} className="quick"><Icon name="bag" />{t('اطلبي الآن')}</Link>
                    </>
                )}
            </div>
            <Link href={`/produits/${p.id}`} className="bd" style={{ display: 'block' }}>
                <h3>{p.name}</h3>
                <span className="sub">{p.subtitle} · {t(CATS[p.category])}</span>
                <div className="row">
                    <span className="pr">
                        {num(p.price)} <small>{t('دج')}</small>
                        {p.old_price && <span className="old">{num(p.old_price)}</span>}
                    </span>
                </div>
            </Link>
        </article>
    );
}
