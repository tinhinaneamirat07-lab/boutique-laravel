import { Head, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import Logo from '../../Components/Logo';
import { useT } from '../../lib';

export default function Login() {
    const { t } = useT();
    const form = useForm({ email: '', password: '', remember: false });
    const submit = (e: FormEvent) => { e.preventDefault(); form.post('/admin/login', { onFinish: () => form.reset('password') }); };

    return (
        <div className="login">
            <Head title="Admin" />
            <form onSubmit={submit}>
                <div style={{ textAlign: 'center', marginBottom: 8 }}><Logo size={48} /></div>
                <h1 style={{ fontSize: 22, textAlign: 'center' }}>{t('لوحة التحكم')}</h1>
                {form.errors.email && <p className="err">{form.errors.email}</p>}
                <div className="fl"><label>{t('البريد الإلكتروني')}</label>
                    <input type="email" dir="ltr" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} required autoFocus /></div>
                <div className="fl"><label>{t('كلمة السر')}</label>
                    <input type="password" value={form.data.password} onChange={(e) => form.setData('password', e.target.value)} required /></div>
                <label style={{ display: 'flex', gap: 8, fontSize: 14 }}>
                    <input type="checkbox" checked={form.data.remember} onChange={(e) => form.setData('remember', e.target.checked)} />{t('تذكرني')}
                </label>
                <button className="btn main" style={{ justifyContent: 'center' }} disabled={form.processing}>{t('دخول')}</button>
            </form>
        </div>
    );
}
