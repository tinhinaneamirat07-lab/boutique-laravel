@extends('layouts.admin')

@section('title', 'المنتوجات')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold">المنتوجات <span class="text-stone-400 text-lg">({{ $products->count() }})</span></h1>
    <a href="{{ route('admin.products.create') }}" class="bg-brand hover:bg-brand-dark text-white font-bold px-4 py-2 rounded-xl">+ منتوج جديد</a>
</div>

<div class="bg-white rounded-2xl border border-stone-200 overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-stone-50 text-stone-500 text-right">
            <tr>
                <th class="p-3">المنتوج</th>
                <th class="p-3">السعر</th>
                <th class="p-3">المخزون</th>
                <th class="p-3">الطلبات</th>
                <th class="p-3">الظهور</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
        @forelse ($products as $product)
            <tr class="{{ $product->is_active ? '' : 'opacity-50' }}">
                <td class="p-3">
                    <div class="flex items-center gap-3">
                        @if ($product->image_url)
                            <img src="{{ $product->image_url }}" class="w-12 h-12 rounded-lg object-cover" alt="">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-stone-100 flex items-center justify-center font-extrabold text-stone-300">{{ mb_substr($product->name, 0, 1) }}</div>
                        @endif
                        <span class="font-bold">{{ $product->name }}</span>
                    </div>
                </td>
                <td class="p-3 whitespace-nowrap"><span dir="ltr">{{ number_format($product->price, 0, ',', ' ') }}</span> دج</td>
                <td class="p-3">
                    @if ($product->stock > 0)
                        {{ $product->stock }}
                    @else
                        <span class="bg-red-100 text-red-700 rounded-lg px-2 py-1 text-xs font-bold">نفذ</span>
                    @endif
                </td>
                <td class="p-3">{{ $product->orders_count }}</td>
                <td class="p-3">
                    <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                        @csrf @method('PATCH')
                        <button class="rounded-lg px-2 py-1 text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-stone-200 text-stone-600' }}">
                            {{ $product->is_active ? 'ظاهر' : 'مخفي' }}
                        </button>
                    </form>
                </td>
                <td class="p-3 whitespace-nowrap text-left">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-brand font-bold ml-3">تعديل</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline"
                          onsubmit="return confirm('حذف هذا المنتوج؟')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 font-bold">حذف</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="p-8 text-center text-stone-400">لا توجد منتوجات</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
