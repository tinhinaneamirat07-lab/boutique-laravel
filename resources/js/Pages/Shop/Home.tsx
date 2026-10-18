import { Link } from '@inertiajs/react';
import { useState } from 'react';
import Layout from '../../Components/Layout';
import ProductCard from '../../Components/ProductCard';
import Icon from '../../Components/Icon';
import { CATS, useT, type Card } from '../../lib';

const CAT_IMG: Record<string, string> = {
    heels: '27204295', sneakers: '5488660', sport: '9207813', boots: '27256473', classic: '27018339', sandals: '26925248',
};
const img = (id: string) => `/maquettes/images/${id}.jpg`;

export default function Home({ newest, counts }: { newest: Card[]; counts: Record<string, number> }) {
    const { t, fr } = useT();
    const [tab, setTab] = useState('all');
    const list = tab === 'all' ? newest : newest.filter((p) => p.category === tab);
    const words = ['كعب عالي', 'Sneakers', 'الدفع عند الاستلام', 'Bottes', 'رياضة', 'Mocassins', '58 ولاية', 'Escarpins'];

    return (
        <Layout>
            <div className="wrap">
                {/* Hero en bento */}
                <div className="bento">
                    <div className="b b-main rv">
                        <img src={img('27204296')} alt="Escarpins noirs" />
                        <span className="chiptop"><i />Collection Automne 26</span>
                        <div className="glass">
                            <div className="th"><img src={img('27204296')} alt="" /></div>
                            <div><b>Escarpin Lalla</b><small>{t('كعب 9 سم')} · 36 – 41</small></div>
                            <Link href="/catalogue?cat=heels" className="plus" aria-label={t('اطلبي الآن')}><Icon name="plus" /></Link>
                        </div>
                    </div>
                    <div className="b b-title rv">
                        <div className="eb"><span>Miza Studio</span><span>N°01</span></div>
                        {fr
                            ? <h1>Chaque <span className="it">pas</span><br />raconte<br />votre style.</h1>
                            : <h1>كل <span className="it">pas</span><br />يحكي<br />ستايلك.</h1>}
                        <p>{t('كعب عالي، سنيكرز، رياضة وبوتين. اطلبي في دقيقة، وادفعي عند الاستلام في 58 ولاية.')}</p>
                        <div className="btns">
                            <Link href="/catalogue" className="btn main">{t('تسوّقي الآن')} <span className="arr"><Icon name="arrow" /></span></Link>
                            <Link href="/sale" className="btn line">Sale -40%</Link>
                        </div>
                    </div>
                    <div className="hero-mosaic">
                        <Link href="/catalogue?cat=sneakers" className="b b-img rv"><img src={img('35618111')} alt="" /><span className="lbl">{t('سنيكرز ←')}</span></Link>
                        <Link href="/catalogue?cat=boots" className="b b-img rv"><img src={img('27256456')} alt="" /><span className="lbl">{t('بوتين ←')}</span></Link>
                    </div>
                </div>

                <div className="marq"><div className="tr">
                    {[...words, ...words].map((w, i) => (
                        <span key={i} style={{ display: 'contents' }}>
                            <span className={i % 2 ? 'it' : ''}>{t(w)}</span><span className="st">✦</span>
                        </span>
                    ))}
                </div></div>

                {/* Catégories */}
                <section className="sec">
                    <div className="hd rv">
                        {fr ? <h2>Trouvez <span className="it">votre</span> style</h2> : <h2>اختاري <span className="it">votre</span> ستايل</h2>}
                    </div>
                    <div className="cats" style={{ gridTemplateColumns: 'repeat(6,1fr)' }}>
                        {Object.entries(CATS).map(([k, name]) => (
                            <Link key={k} href={`/catalogue?cat=${k}`} className="cat rv">
                                <div className="ph"><img src={img(CAT_IMG[k])} alt="" /></div>
                                <b>{t(name)}</b><small>{counts[k] ?? 0} {t('موديل')}</small>
                            </Link>
                        ))}
                    </div>
                </section>

                {/* Nouveautés avec onglets */}
                <section className="sec">
                    <div className="hd rv">
                        {fr ? <h2>Les <span className="it">nouveautés</span></h2> : <h2>وصل <span className="it">nouveau</span></h2>}
                        <div className="tabs">
                            {['all', 'heels', 'sneakers', 'sport', 'boots'].map((c) => (
                                <button key={c} className={tab === c ? 'on' : ''} onClick={() => setTab(c)}>
                                    {c === 'all' ? t('الكل') : t(CATS[c])}
                                </button>
                            ))}
                        </div>
                    </div>
                    <div className="grid">{list.map((p) => <ProductCard key={p.id} p={p} />)}</div>
                    <div className="more"><Link href="/catalogue" className="btn line">{t('شوفي كل الأحذية')}</Link></div>
                </section>

                {/* Édito sport */}
                <section className="sec">
                    <div className="edito rv" style={{ background: 'var(--cream)' }}>
                        <div className="huge">sport</div>
                        <div className="txt">
                            <span className="it" style={{ fontSize: 24, color: 'var(--soil)' }}>move in style</span>
                            {fr ? <h2>De la <span className="it">gym</span><br />à la rue.</h2> : <h2>من الـ <span className="it">gym</span><br />حتى الشارع.</h2>}
                            <p style={{ maxWidth: 420 }}>{t('سنيكرز ورياضة خفاف ومريحين، بألوان تمشي مع كل لبسة.')}</p>
                            <Link href="/catalogue?cat=sport" className="btn main" style={{ alignSelf: 'flex-start' }}>{t('اكتشفي الرياضة')} <span className="arr"><Icon name="arrow" /></span></Link>
                        </div>
                        <div className="img"><span className="sticker" style={{ background: 'var(--sky)', color: 'var(--java)' }}>{t('جديد')}<br />Sport<br />2026</span><img src={img('9207813')} alt="" /></div>
                    </div>
                </section>

                {/* Étapes */}
                <section className="sec">
                    <div className="hd rv">{fr ? <h2>4 étapes <span className="it">simples</span></h2> : <h2>4 خطوات <span className="it">simples</span></h2>}</div>
                    <div className="steps">
                        {[['اختاري المقاس', 'من 35 حتى 45، ودليل المقاسات معاكِ.'], ['املئي معلوماتك', 'الاسم، الهاتف والولاية. بلا حساب.'],
                          ['نعيطولك', 'نأكدو الطلب والمقاس بالتيليفون.'], ['خلّصي كي توصلك', 'للمنزل ولا في Stop desk.']].map(([h, p], i) => (
                            <div key={h} className="step rv"><span className="n">0{i + 1}</span><div><h4>{t(h)}</h4><p>{t(p)}</p></div></div>
                        ))}
                    </div>
                </section>
            </div>
        </Layout>
    );
}
