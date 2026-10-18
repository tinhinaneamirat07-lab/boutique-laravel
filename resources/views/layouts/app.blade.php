{{-- Layout = le squelette commun à toutes les pages (header, footer). --}}
<!DOCTYPE html>
{{-- dir : rtl (droite à gauche) en arabe, ltr en français --}}
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Boutique') — Slide & Web</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])  {{-- charge Tailwind + Alpine --}}
</head>
<body class="bg-cream text-stone-800 font-sans antialiased min-h-screen flex flex-col">

    <header class="bg-white border-b border-stone-200">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('products.index') }}" class="text-2xl font-extrabold text-brand">{{ __('بوتيك') }}</a>
            <div class="flex gap-2 text-sm">
                @foreach (['ar' => 'AR', 'fr' => 'FR'] as $code => $label)
                    <a href="{{ route('locale', $code) }}"
                       class="px-2 py-1 rounded {{ app()->getLocale() === $code ? 'bg-stone-100 font-bold' : 'text-stone-400 hover:text-stone-700' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        @yield('content')  {{-- ici s'insère le contenu de chaque page --}}
    </main>

    <footer class="border-t border-stone-200 py-6 text-center text-sm text-stone-500">
        {{ __('الدفع عند الاستلام · التوصيل لـ 58 ولاية') }}
    </footer>
</body>
</html>
