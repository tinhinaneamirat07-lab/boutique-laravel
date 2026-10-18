{{-- Layout de l'admin : menu à droite + contenu --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-800 font-sans antialiased">
<div class="md:flex min-h-screen">
    <aside class="md:w-56 bg-stone-900 text-stone-300 p-4 flex md:flex-col gap-1 overflow-x-auto">
        <a href="{{ route('admin.orders.index') }}" class="text-white text-xl font-extrabold mb-4 hidden md:block">لوحة التحكم</a>
        @php
            // Menu : [route, libellé, disponible ?]
            $menu = [
                ['admin.orders.index', 'الطلبات', true],
                ['admin.products.index', 'المنتوجات', true],
                ['admin.wilayas.index', 'التوصيل', true],
                ['admin.stats.index', 'الإحصائيات', true],
            ];
        @endphp
        @foreach ($menu as [$route, $label, $ready])
            @if ($ready)
                <a href="{{ route($route) }}"
                   class="px-3 py-2 rounded-lg whitespace-nowrap {{ request()->routeIs(str_replace('.index', '.*', $route)) ? 'bg-brand text-white font-bold' : 'hover:bg-stone-800' }}">{{ $label }}</a>
            @else
                <span class="px-3 py-2 rounded-lg text-stone-600 whitespace-nowrap">{{ $label }} <small>(قريباً)</small></span>
            @endif
        @endforeach

        <form method="POST" action="{{ route('admin.logout') }}" class="md:mt-auto">
            @csrf
            <button class="px-3 py-2 rounded-lg hover:bg-stone-800 w-full text-right whitespace-nowrap">خروج</button>
        </form>
    </aside>

    <main class="flex-1 p-4 md:p-8 min-w-0">
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 rounded-xl px-4 py-3">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
