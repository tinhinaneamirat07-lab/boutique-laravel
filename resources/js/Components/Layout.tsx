import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, type ReactNode } from 'react';
import Icon from './Icon';
import Logo from './Logo';
import { useT } from '../lib';

// Squelette commun : barre de navigation flottante + pied de page
export default function Layout({ title, children }: { title?: string; children: ReactNode }) {
    const { t, locale } = useT();
    const { url } = usePage();

    // apparition des blocs .rv au défilement
    useEffect(() => {
        const io = new IntersectionObserver((es) => es.forEach((e) => {
            if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
        }), { threshold: 0.1 });
        document.querySelectorAll('.rv:not(.in)').forEach((el, i) => {
            (el as HTMLElement).style.transitionDelay = `${(i % 4) * 70}ms`;
            io.observe(el);
        });
        return () => io.disconnect();
    }, [url]);

    const links: [string, string][] = [
        ['/catalogue', 'الكل'], ['/catalogue?cat=heels', 'كعب عالي'], ['/catalogue?cat=sneakers', 'سنيكرز'],
        ['/catalogue?cat=sport', 'رياضة'], ['/catalogue?cat=boots', 'بوتين'],
    ];

    return (
        <div className="page-in">
            <Head title={title} />
            <div className="nav"><div className="in">
                <Link href="/"><Logo /></Link>
                <div className="links">
                    {links.map(([href, label]) => (
                        <Link key={href} href={href} className={url === href ? 'cur' : ''}>{t(label)}</Link>
                    ))}
                    <Link href="/sale" style={{ color: 'var(--sceptre)', fontWeight: 600 }}>Sale</Link>
                </div>
                <div className="tools">
                    {/* lien normal (pas Inertia) : toute la page se recharge dans la nouvelle direction */}
                    <div className="lang">
                        <a href="/langue/ar" className={locale === 'ar' ? 'on' : ''}>AR</a>
                        <a href="/langue/fr" className={locale === 'fr' ? 'on' : ''}>FR</a>
                    </div>
                    <Link href="/catalogue" className="ico" aria-label={t('بحث')}><Icon name="search" /></Link>
                </div>
            </div></div>

            {children}

            <footer className="dk">
                <div className="top">
                    <div>
                        <h5>NEWSLETTER</h5>
                        <p style={{ opacity: .8 }}>{t('أول وحدة تعرفي بالموديلات الجداد والتخفيضات.')}</p>
                        <form className="news" onSubmit={(e) => e.preventDefault()}>
                            <input placeholder={t('رقم الهاتف أو الإيميل')} /><button>{t('اشتركي')}</button>
                        </form>
                    </div>
                    <div><h5>BOUTIQUE</h5><ul>
                        <li><Link href="/catalogue?cat=heels">{t('كعب عالي')}</Link></li>
                        <li><Link href="/catalogue?cat=sneakers">{t('سنيكرز')}</Link></li>
                        <li><Link href="/catalogue?cat=sport">{t('رياضة')}</Link></li>
                        <li><Link href="/catalogue?cat=boots">{t('بوتين')}</Link></li>
                        <li><Link href="/sale">Sale</Link></li>
                    </ul></div>
                    <div><h5>AIDE</h5><ul><li>{t('دليل المقاسات')}</li><li>{t('أسعار التوصيل')}</li><li>{t('تتبع الطلب')}</li></ul></div>
                    <div><h5>SUIVEZ-NOUS</h5><ul><li>Instagram</li><li>TikTok</li><li>Facebook</li></ul></div>
                </div>
                <div className="big"><Logo size={0} /></div>
                <div className="cp"><span>© 2026 Miza</span><span>Projet démo · Slide &amp; Web</span></div>
            </footer>
        </div>
    );
}
