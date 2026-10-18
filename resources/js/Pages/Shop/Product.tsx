import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import Layout from '../../Components/Layout';
import ProductCard from '../../Components/ProductCard';
import Icon from '../../Components/Icon';
import { CATS, num, useT, type Card } from '../../lib';

type Wilaya = { id: number; name: string; home: number; desk: number };
type Props = { product: Card & { description: string | null; sizes: Record<string, number> }; wilayas: Wilaya[]; related: Card[] };

export default function Product({ product: p, wilayas, related }: Props) {
    const { t } = useT();

    // useForm (Inertia) : garde les valeurs, envoie le POST et récupère les erreurs de validation de Laravel
    const form = useForm({
        product_id: p.id, size: '', quantity: 1,
        customer_name: '', phone: '', wilaya_id: '', commune: '', delivery_type: 'home',
    });
    const { data, setData, errors, processing } = form;

    const sizes = Object.entries(p.sizes);
    const left = data.size ? p.sizes[data.size] ?? 0 : 0;
    const w = wilayas.find((x) => String(x.id) === String(data.wilaya_id));
    const shipping = w ? (data.delivery_type === 'desk' ? w.desk : w.home) : 0;
    // total affiché seulement à titre indicatif : Laravel recalcule tout côté serveur
    const total = p.price * data.quantity + shipping;
    const da = (n: number) => `${num(n)} ${t('دج')}`;

    const submit = (e: FormEvent) => { e.preventDefault(); form.post('/commandes'); };

    return (
        <Layout title={p.name}>
            <div className="wrap">
                <div className="pdp">
                    <div className="gal">
                        <div className="m">{p.image && <img src={p.image} alt={p.name} />}
                            <span className="zoomtag"><Icon name="zoom" size={16} />{t('كبّري الصورة')}</span></div>
                    </div>

                    <form className="buy" onSubmit={submit}>
                        <div className="crumb"><span>{t('الرئيسية')}</span>/<span>{t(CATS[p.category])}</span>/<span>{p.name}</span></div>
                        <span className="it">{p.subtitle}</span>
                        <h1>{p.name}</h1>
                        <div className="prow">
                            <span className="pr">{num(p.price)} <small>{t('دج')}</small>{p.old_price && <span className="old">{num(p.old_price)}</span>}</span>
                            {p.stock > 0 && <span className="pill">{t('متوفر')}</span>}
                        </div>
                        {p.description && <p style={{ color: 'var(--soil)', marginTop: 8 }}>{p.description}</p>}

                        {p.stock === 0 ? (
                            <p className="pill" style={{ background: '#eee', color: '#777', marginTop: 20 }}>{t('نفذ المخزون')}</p>
                        ) : (
                            <>
                                <div className="lb"><span>{t('المقاس')}</span><a href="#">{t('دليل المقاسات')}</a></div>
                                <div className="sz">
                                    {sizes.map(([s, q]) => (
                                        <button type="button" key={s} disabled={q === 0} className={data.size === s ? 'on' : ''}
                                            onClick={() => { setData((d) => ({ ...d, size: s, quantity: 1 })); }}>{s}</button>
                                    ))}
                                </div>
                                {errors.size && <p className="err">{errors.size}</p>}

                                <div className="lb"><span>{t('معلومات التوصيل')}</span></div>
                                <div className="fields">
                                    <div className="fl full"><label>{t('الاسم الكامل')}</label>
                                        <input value={data.customer_name} onChange={(e) => setData('customer_name', e.target.value)} required /></div>
                                    <div className="fl"><label>{t('رقم الهاتف')}</label>
                                        <input dir="ltr" inputMode="tel" placeholder="0550 12 34 56" value={data.phone} onChange={(e) => setData('phone', e.target.value.replace(/\s/g, ''))} required /></div>
                                    <div className="fl"><label>{t('الولاية')}</label>
                                        <select value={data.wilaya_id} onChange={(e) => setData('wilaya_id', e.target.value)} required>
                                            <option value="">{t('اختر...')}</option>
                                            {wilayas.map((x) => <option key={x.id} value={x.id}>{x.id} - {x.name}</option>)}
                                        </select></div>
                                    <div className="fl full"><label>{t('البلدية')}</label>
                                        <input value={data.commune} onChange={(e) => setData('commune', e.target.value)} required /></div>
                                </div>
                                {['customer_name', 'phone', 'wilaya_id', 'commune'].map((k) => errors[k as keyof typeof errors] && <p key={k} className="err">{errors[k as keyof typeof errors]}</p>)}

                                <div className="lb"><span>{t('نوع التوصيل')}</span></div>
                                <div className="seg">
                                    <span className="knob" style={{ transform: data.delivery_type === 'desk' ? `translateX(${document.dir === 'ltr' ? '100%' : '-100%'})` : 'none' }} />
                                    <button type="button" onClick={() => setData('delivery_type', 'home')}>{t('للمنزل')}<b>{w ? da(w.home) : '—'}</b></button>
                                    <button type="button" onClick={() => setData('delivery_type', 'desk')}>Stop desk<b>{w ? da(w.desk) : '—'}</b></button>
                                </div>

                                <div className="sum">
                                    <div><span><bdi>{p.name}</bdi>{data.size && <> · {t('مقاس')} {data.size}</>} × {data.quantity}</span><span>{da(p.price * data.quantity)}</span></div>
                                    <div><span>{t('التوصيل')}</span><span>{w ? da(shipping) : t('اختر الولاية')}</span></div>
                                    <div className="tot"><span>{t('المجموع')}</span><span>{da(total)}</span></div>
                                </div>
                                <div className="cta">
                                    <div className="qty">
                                        <button type="button" onClick={() => data.quantity < Math.max(1, left) && setData('quantity', data.quantity + 1)}>+</button>
                                        <span>{data.quantity}</span>
                                        <button type="button" onClick={() => data.quantity > 1 && setData('quantity', data.quantity - 1)}>−</button>
                                    </div>
                                    <button className="btn main" disabled={processing}>{t('تأكيد الطلب')} <span className="arr"><Icon name="arrow" /></span></button>
                                </div>
                                <div className="perks">
                                    <div><Icon name="truck" />{t('توصيل 24–72 سا')}</div>
                                    <div><Icon name="cash" />{t('الدفع عند الاستلام')}</div>
                                    <div><Icon name="phone" />{t('نعيطولك للتأكيد')}</div>
                                </div>
                            </>
                        )}
                    </form>
                </div>

                {related.length > 0 && (
                    <section className="sec">
                        <div className="hd"><h2>{t('يمشي')} <span className="it">avec</span></h2></div>
                        <div className="grid">{related.map((r) => <ProductCard key={r.id} p={r} />)}</div>
                    </section>
                )}
            </div>
        </Layout>
    );
}
