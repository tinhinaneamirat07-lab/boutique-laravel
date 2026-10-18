import { Link } from '@inertiajs/react';
import Layout from '../../Components/Layout';
import { num, useT } from '../../lib';

type Order = {
    id: number; name: string; phone: string; product: string; image: string | null; size: string | null;
    quantity: number; type: 'home' | 'desk'; wilaya: string; subtotal: number; shipping: number; total: number;
};

export default function Thanks({ order: o }: { order: Order }) {
    const { t } = useT();
    return (
        <Layout title={t('شكراً')}>
            <div className="thx">
                <div className="tcard">
                    <svg className="chk" viewBox="0 0 92 92"><circle cx="46" cy="46" r="46" /><path d="M28 47l12 12 24-26" /></svg>
                    <h1>{t('شكراً')} <span className="it"><bdi>{o.name}</bdi></span>!</h1>
                    <p>{t('طلبك وصلنا. نعيطولك قريب على')} <b dir="ltr">{o.phone}</b> {t('باش نأكدو.')}</p>
                    <span className="onum">Commande #{o.id}</span>
                    <div className="track">
                        <div className="ok"><i>✓</i>{t('تم الطلب')}</div>
                        <div><i>2</i>{t('التأكيد')}</div>
                        <div><i>3</i>{t('الشحن')}</div>
                        <div><i>4</i>{t('الاستلام')}</div>
                    </div>
                    <div className="rc">
                        {o.image && <div className="t"><img src={o.image} alt="" /></div>}
                        <div><b>{o.product}</b>{t('مقاس')} {o.size} × {o.quantity} · {o.type === 'home' ? t('للمنزل') : 'Stop desk'} · {o.wilaya}</div>
                        <strong>{num(o.total)} {t('دج')}</strong>
                    </div>
                    <Link href="/" className="btn line" style={{ marginTop: 22 }}>{t('مواصلة التسوق')}</Link>
                </div>
            </div>
        </Layout>
    );
}
