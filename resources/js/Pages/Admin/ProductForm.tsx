import { Link, useForm } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AdminLayout from '../../Components/AdminLayout';
import { CATS, num, useT } from '../../lib';

type Product = { id: number; name: string; name_fr: string | null; category: string; subtitle: string | null;
    description: string | null; description_fr: string | null; price: number; old_price: number | null;
    sizes: Record<string, number> | null; is_active: boolean; is_new: boolean; image: string | null };

const ALL_SIZES = ['35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45'];

export default function ProductForm({ product: p }: { product: Product | null }) {
    const { t } = useT();
    const editing = !!p;
    const [preview, setPreview] = useState<string | null>(p?.image ?? null);

    // pointures : toutes les pointures possibles, 0 si absentes
    const startSizes = Object.fromEntries(ALL_SIZES.map((s) => [s, p?.sizes?.[s] ?? 0]));

    const form = useForm({
        name: p?.name ?? '', name_fr: p?.name_fr ?? '', category: p?.category ?? 'heels', subtitle: p?.subtitle ?? '',
        description: p?.description ?? '', description_fr: p?.description_fr ?? '',
        price: p?.price ?? 0, old_price: p?.old_price ?? '', sizes: startSizes as Record<string, number>,
        is_active: p?.is_active ?? true, is_new: p?.is_new ?? true, image: null as File | null,
        _method: editing ? 'put' : 'post',   // fichier + modification : POST avec _method=PUT
    });
    const { data, setData, errors, processing } = form;
    const total = Object.values(data.sizes).reduce((a, b) => a + Number(b || 0), 0);

    const submit = (e: FormEvent) => {
        e.preventDefault();
        form.post(editing ? `/admin/produits/${p!.id}` : '/admin/produits', { forceFormData: true });
    };
    const Err = ({ k }: { k: string }) => (errors as Record<string, string>)[k] ? <p className="err">{(errors as Record<string, string>)[k]}</p> : null;

    return (
        <AdminLayout title={editing ? 'تعديل' : 'منتوج جديد'}>
            <form onSubmit={submit} style={{ display: 'contents' }}>
                <div className="atop">
                    <div><h1>{editing ? `${t('تعديل')} : ${p!.name}` : t('منتوج جديد')}</h1><p>{t('المنتوجات')}</p></div>
                    <div style={{ display: 'flex', gap: 10 }}>
                        <Link href="/admin/produits" className="abtn gh">{t('إلغاء')}</Link>
                        <button className="abtn" disabled={processing}>{t('حفظ ونشر')}</button>
                    </div>
                </div>
                <div className="form2">
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                        <div className="box"><h3>{t('المعلومات')}</h3>
                            <div className="fields">
                                <div className="fl"><label>{t('الاسم (عربي)')}</label><input value={data.name} onChange={(e) => setData('name', e.target.value)} required /><Err k="name" /></div>
                                <div className="fl"><label>Nom (français)</label><input dir="ltr" value={data.name_fr} onChange={(e) => setData('name_fr', e.target.value)} /></div>
                                <div className="fl full"><label>{t('الوصف')}</label><textarea value={data.description} onChange={(e) => setData('description', e.target.value)} /></div>
                                <div className="fl full"><label>Description (français)</label><textarea dir="ltr" value={data.description_fr} onChange={(e) => setData('description_fr', e.target.value)} /></div>
                            </div>
                        </div>
                        <div className="box"><h3>{t('الصور')}</h3>
                            <div className="thumbs3">{preview && <div className="main"><img src={preview} alt="" /></div>}</div>
                            <label className="drop" style={{ cursor: 'pointer' }}>
                                {t('اسحبي الصور هنا ولا')} <b>{t('اختاري من الجهاز')}</b><br /><small>JPG / PNG · 2 Mo max</small>
                                <input type="file" accept="image/*" hidden onChange={(e) => {
                                    const f = e.target.files?.[0] ?? null;
                                    setData('image', f);
                                    if (f) setPreview(URL.createObjectURL(f));   // aperçu avant l'envoi
                                }} />
                            </label>
                            <Err k="image" />
                        </div>
                        <div className="box"><h3>{t('المقاسات والمخزون')}</h3>
                            <div className="sizetab">
                                {ALL_SIZES.map((s) => (
                                    <div key={s} className={Number(data.sizes[s]) ? '' : 'z'}><b>{s}</b>
                                        <input type="number" min={0} value={data.sizes[s]} onChange={(e) => setData('sizes', { ...data.sizes, [s]: Number(e.target.value) })} />
                                    </div>
                                ))}
                            </div>
                            <div className="rowsw"><span>{t('المجموع في المخزون')}</span><b className="stk">{total} {t('زوج')}</b></div>
                        </div>
                    </div>
                    <div style={{ display: 'flex', flexDirection: 'column', gap: 14 }}>
                        <div className="box"><h3>{t('السعر')}</h3>
                            <div className="fields">
                                <div className="fl"><label>{t('السعر (دج)')}</label><input type="number" min={0} value={data.price} onChange={(e) => setData('price', Number(e.target.value))} required /><Err k="price" /></div>
                                <div className="fl"><label>{t('السعر القديم (Sale)')}</label><input type="number" min={0} placeholder={t('اختياري')} value={data.old_price} onChange={(e) => setData('old_price', e.target.value)} /><Err k="old_price" /></div>
                            </div>
                        </div>
                        <div className="box"><h3>{t('التصنيف')}</h3>
                            <div className="fl"><label>{t('الفئة')}</label>
                                <select value={data.category} onChange={(e) => setData('category', e.target.value)}>
                                    {Object.entries(CATS).map(([k, n]) => <option key={k} value={k}>{t(n)}</option>)}
                                </select></div>
                            <div className="fl"><label>{t('اللون / المادة')}</label><input value={data.subtitle} onChange={(e) => setData('subtitle', e.target.value)} placeholder="Cuir noir" /></div>
                            <div className="rowsw"><span>{t('ظاهر في البوتيك')}</span><button type="button" className={`sw ${data.is_active ? 'on' : ''}`} onClick={() => setData('is_active', !data.is_active)} /></div>
                            <div className="rowsw"><span>{t('علامة «جديد»')}</span><button type="button" className={`sw ${data.is_new ? 'on' : ''}`} onClick={() => setData('is_new', !data.is_new)} /></div>
                        </div>
                        <div className="box"><h3>{t('معاينة')}</h3>
                            <div className="prev"><div className="ph">{preview && <img src={preview} alt="" />}{data.is_new && <span className="tag">{t('جديد')}</span>}</div>
                                <div style={{ padding: '12px 6px 4px' }}><h3 style={{ fontSize: 16, fontWeight: 500 }}>{data.name_fr || data.name || '—'}</h3>
                                    <small style={{ color: 'var(--soil)' }}>{data.subtitle}</small>
                                    <div style={{ marginTop: 8 }}><span className="pr">{num(Number(data.price) || 0)} <small>{t('دج')}</small></span></div></div></div>
                        </div>
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
