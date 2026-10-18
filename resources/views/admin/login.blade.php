<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>دخول المسؤول</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 font-sans min-h-screen flex items-center justify-center p-4">
    <form method="POST" action="{{ route('admin.login') }}" class="bg-white rounded-2xl border border-stone-200 p-8 w-full max-w-sm space-y-4">
        @csrf
        <h1 class="text-2xl font-extrabold text-center">لوحة التحكم</h1>

        @error('email') <p class="bg-red-50 text-red-700 rounded-xl p-3 text-sm">{{ $message }}</p> @enderror

        <div>
            <label class="block text-sm font-bold mb-1">البريد الإلكتروني</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus dir="ltr"
                   class="w-full border border-stone-300 rounded-xl px-3 py-2 text-right">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">كلمة السر</label>
            <input name="password" type="password" required class="w-full border border-stone-300 rounded-xl px-3 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> تذكرني</label>
        <button class="w-full bg-brand hover:bg-brand-dark text-white font-extrabold py-3 rounded-xl">دخول</button>
    </form>
</body>
</html>
