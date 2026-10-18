@extends('layouts.admin')

@section('title', 'التوصيل')

@section('content')
{{-- Un seul formulaire pour les 58 lignes --}}
<form method="POST" action="{{ route('admin.wilayas.update') }}" x-data="{ q: '' }">
    @csrf @method('PUT')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-extrabold">أسعار التوصيل</h1>
        <div class="flex gap-2">
            {{-- Recherche instantanée (Alpine) : masque les lignes qui ne correspondent pas --}}
            <input x-model="q" placeholder="ابحث عن ولاية..." class="border border-stone-300 rounded-xl px-3 py-2 bg-white">
            <button class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-2 rounded-xl">حفظ الكل</button>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 rounded-xl p-3 text-sm mb-4">تحقق من الأسعار (أرقام موجبة فقط).</div>
    @endif

    <div class="bg-white rounded-2xl border border-stone-200 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-stone-50 text-stone-500 text-right">
                <tr>
                    <th class="p-3">الرمز</th>
                    <th class="p-3">الولاية</th>
                    <th class="p-3">للمنزل (دج)</th>
                    <th class="p-3">Stop desk (دج)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
            @foreach ($wilayas as $w)
                {{-- @js : texte sûr pour JavaScript (ex : l'apostrophe de M'Sila) --}}
                <tr x-show="@js($w->id.' '.$w->name_ar.' '.mb_strtolower($w->name_fr)).includes(q.toLowerCase())">
                    <td class="p-3 font-bold text-stone-400">{{ str_pad($w->id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="p-3 font-bold">{{ $w->name_ar }} <span class="text-stone-400 font-normal">· {{ $w->name_fr }}</span></td>
                    <td class="p-3">
                        <input type="number" min="0" name="prices[{{ $w->id }}][home]" value="{{ old("prices.$w->id.home", $w->home_price) }}"
                               class="w-28 border border-stone-300 rounded-lg px-2 py-1">
                    </td>
                    <td class="p-3">
                        <input type="number" min="0" name="prices[{{ $w->id }}][desk]" value="{{ old("prices.$w->id.desk", $w->desk_price) }}"
                               class="w-28 border border-stone-300 rounded-lg px-2 py-1">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</form>
@endsection
