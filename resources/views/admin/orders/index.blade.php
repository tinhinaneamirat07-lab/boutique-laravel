@extends('layouts.admin')

@section('title', 'الطلبات')

@section('content')
<div class="flex flex-wrap items-end justify-between gap-4 mb-6">
    <h1 class="text-2xl font-extrabold">الطلبات <span class="text-stone-400 text-lg">({{ $orders->total() }})</span></h1>

    {{-- Filtres : formulaire GET -> les filtres apparaissent dans l'URL --}}
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="status" onchange="this.form.submit()" class="border border-stone-300 rounded-xl px-3 py-2 bg-white">
            <option value="">كل الحالات</option>
            @foreach ($statuses as $key => [$label])
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="wilaya" onchange="this.form.submit()" class="border border-stone-300 rounded-xl px-3 py-2 bg-white">
            <option value="">كل الولايات</option>
            @foreach ($wilayas as $w)
                <option value="{{ $w->id }}" @selected(request('wilaya') == $w->id)>{{ $w->id }} - {{ $w->name_ar }}</option>
            @endforeach
        </select>
        @if (request('status') || request('wilaya'))
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-2 text-brand font-bold">مسح</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 text-right">
            <tr>
                <th class="p-3">#</th>
                <th class="p-3">الزبون</th>
                <th class="p-3">المنتوج</th>
                <th class="p-3">التوصيل</th>
                <th class="p-3">المجموع</th>
                <th class="p-3">الحالة</th>
                <th class="p-3">التاريخ</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
        @forelse ($orders as $order)
            <tr class="align-top">
                <td class="p-3 font-bold">{{ $order->id }}</td>
                <td class="p-3">
                    <div class="font-bold">{{ $order->customer_name }}</div>
                    {{-- tel: ouvre l'appel directement sur téléphone --}}
                    <a href="tel:{{ $order->phone }}" dir="ltr"
                       class="inline-flex items-center gap-1 mt-1 bg-green-600 hover:bg-green-700 text-white rounded-lg px-2 py-1 text-xs font-bold">
                        📞 {{ $order->phone }}
                    </a>
                </td>
                <td class="p-3">{{ $order->product->name }} × {{ $order->quantity }}</td>
                <td class="p-3">
                    {{ $order->wilaya->id }} - {{ $order->wilaya->name_ar }}<br>
                    <span class="text-stone-500">{{ $order->commune }} · {{ $order->delivery_type === 'home' ? 'للمنزل' : 'Stop desk' }}</span>
                </td>
                <td class="p-3 font-bold whitespace-nowrap"><span dir="ltr">{{ number_format($order->total, 0, ',', ' ') }}</span> دج</td>
                <td class="p-3">
                    {{-- Changer le statut : la liste envoie le formulaire dès qu'on choisit --}}
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                class="rounded-lg px-2 py-1 font-bold border-0 {{ $statuses[$order->status][1] }}">
                            @foreach ($statuses as $key => [$label])
                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="p-3 text-stone-500 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="p-8 text-center text-stone-400">لا توجد طلبات</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $orders->links() }}</div>
@endsection
