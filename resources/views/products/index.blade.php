@extends('layouts.app')  {{-- utilise le squelette layouts/app --}}

@section('title', __('المنتوجات'))

@section('content')
    <h1 class="text-3xl font-extrabold mb-2">{{ __('منتوجاتنا') }}</h1>
    <p class="text-stone-500 mb-8">{{ __('اطلب الآن وادفع عند الاستلام') }}</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        @foreach ($products as $product)   {{-- boucle : une carte par produit --}}
            <article class="bg-white rounded-2xl overflow-hidden border border-stone-200 flex flex-col">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->display_name }}" class="aspect-square w-full object-cover ">
                @else
                    <div class="aspect-square bg-stone-100 flex items-center justify-center text-5xl font-extrabold text-stone-300">
                    {{ mb_substr($product->name, 0, 1) }}  {{-- image temporaire : 1re lettre --}}
                </div>
                @endif
                <div class="p-4 flex flex-col flex-1">
                    <h2 class="font-bold mb-1">{{ $product->display_name }}</h2>
                    <p class="text-brand font-extrabold text-lg mb-4"><span dir="ltr">{{ number_format($product->price, 0, ',', ' ') }}</span> {{ __('دج') }}</p>

                    @if ($product->stock > 0)
                        <a href="{{ route("products.show", $product) }}"
                           class="mt-auto text-center bg-brand hover:bg-brand-dark text-white font-bold py-2 rounded-xl transition">
                            {{ __('اطلب الآن') }}
                        </a>
                    @else
                        <span class="mt-auto text-center bg-stone-100 text-stone-400 font-bold py-2 rounded-xl">{{ __('نفذ المخزون') }}</span>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endsection
