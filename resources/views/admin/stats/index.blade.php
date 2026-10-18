@extends('layouts.admin')

@section('title', 'الإحصائيات')

@php
    // Petit helper d'affichage : 12500 -> "12 500"
    $fmt = fn ($n) => number_format($n, 0, ',', ' ');
@endphp

@section('content')
<h1 class="text-2xl font-extrabold mb-6">الإحصائيات</h1>

{{-- Cartes chiffres clés --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @foreach ([
        ['طلبات اليوم', $stats['today'], ''],
        ['طلبات الشهر', $stats['month'], ''],
        ['في انتظار التأكيد', $stats['pending'], 'text-blue-700'],
        ['مداخيل الشهر (مُسلّمة)', $fmt($stats['revenue_month']).' دج', 'text-green-700'],
        ['مداخيل متوقعة (الشهر)', $fmt($stats['expected_month']).' دج', 'text-stone-500'],
    ] as [$label, $value, $color])
        <div class="bg-white rounded-2xl border border-stone-200 p-4">
            <div class="text-sm text-stone-500">{{ $label }}</div>
            <div class="text-2xl font-extrabold mt-1 {{ $color }}" dir="auto">{{ $value }}</div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Top produits --}}
    <section class="bg-white rounded-2xl border border-stone-200 p-5">
        <h2 class="font-extrabold mb-4">الأكثر مبيعاً</h2>
        @php $maxQty = $topProducts->max('qty') ?: 1; @endphp
        @forelse ($topProducts as $row)
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-bold">{{ $row->product->name }}</span>
                    <span class="text-stone-500">{{ $row->qty }} قطعة</span>
                </div>
                {{-- Barre : largeur proportionnelle au meilleur produit --}}
                <div class="h-2 bg-stone-100 rounded-full"><div class="h-2 bg-brand rounded-full" style="width: {{ $row->qty / $maxQty * 100 }}%"></div></div>
            </div>
        @empty
            <p class="text-stone-400 text-sm">لا توجد بيانات بعد</p>
        @endforelse
    </section>

    {{-- Top wilayas --}}
    <section class="bg-white rounded-2xl border border-stone-200 p-5">
        <h2 class="font-extrabold mb-4">أكثر الولايات طلباً</h2>
        @php $maxN = $topWilayas->max('n') ?: 1; @endphp
        @forelse ($topWilayas as $row)
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-bold">{{ $row->wilaya->id }} - {{ $row->wilaya->name_ar }}</span>
                    <span class="text-stone-500">{{ $row->n }} طلب</span>
                </div>
                <div class="h-2 bg-stone-100 rounded-full"><div class="h-2 bg-stone-700 rounded-full" style="width: {{ $row->n / $maxN * 100 }}%"></div></div>
            </div>
        @empty
            <p class="text-stone-400 text-sm">لا توجد بيانات بعد</p>
        @endforelse
    </section>

    {{-- Répartition par statut --}}
    <section class="bg-white rounded-2xl border border-stone-200 p-5">
        <h2 class="font-extrabold mb-4">حسب الحالة</h2>
        @foreach ($statuses as $key => [$label, $classes])
            <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
               class="flex justify-between items-center py-2 border-b border-stone-100 last:border-0 hover:bg-stone-50 -mx-2 px-2 rounded">
                <span class="rounded-lg px-2 py-1 text-xs font-bold {{ $classes }}">{{ $label }}</span>
                <span class="font-extrabold">{{ $byStatus[$key] ?? 0 }}</span>
            </a>
        @endforeach
    </section>
</div>
@endsection
