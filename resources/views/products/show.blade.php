@extends('layouts.app')

@section('title', $product->display_name)

@section('content')
{{--
  x-data (Alpine) = les variables de la page côté navigateur.
  Quand on change la wilaya, le type ou la quantité, le total se recalcule tout seul.
  old(...) = remet ce que le client avait tapé si la validation échoue.
--}}
<div class="grid md:grid-cols-2 gap-8"
     x-data="{
        wilayas: @js($wilayas),
        price: {{ $product->price }},
        stock: {{ $product->stock }},
        qty: {{ (int) old('quantity', 1) }},
        wilaya: '{{ old('wilaya_id') }}',
        type: '{{ old('delivery_type', 'home') }}',
        get shipping() {
            const w = this.wilayas.find(w => w.code == this.wilaya);
            return w ? w[this.type] : 0;
        },
        get total() { return this.price * this.qty + this.shipping; },
        money(n) { return n.toLocaleString('fr-FR'); }
     }">

    {{-- Colonne 1 : le produit --}}
    <div>
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->display_name }}" class="aspect-square w-full object-cover rounded-2xl ">
        @else
            <div class="aspect-square bg-white border border-stone-200 rounded-2xl flex items-center justify-center text-8xl font-extrabold text-stone-200">
            {{ mb_substr($product->name, 0, 1) }}
        </div>
        @endif
        <h1 class="text-3xl font-extrabold mt-6">{{ $product->display_name }}</h1>
        <p class="text-brand text-2xl font-extrabold mt-2"><span dir="ltr">{{ number_format($product->price, 0, ',', ' ') }}</span> {{ __('دج') }}</p>
        <p class="text-stone-600 mt-4 leading-relaxed">{{ $product->display_description }}</p>
    </div>

    {{-- Colonne 2 : formulaire de commande --}}
    @if ($product->stock < 1)
        <div class="bg-white border border-stone-200 rounded-2xl p-6 h-fit text-center">
            <p class="text-xl font-extrabold text-stone-400">{{ __('نفذ المخزون') }}</p>
            <a href="{{ route('products.index') }}" class="inline-block mt-4 text-brand font-bold">{{ __('← شوف منتوجات أخرى') }}</a>
        </div>
    @else
    <form method="POST" action="{{ route('orders.store') }}"
          class="bg-white border border-stone-200 rounded-2xl p-6 space-y-4 h-fit">
        @csrf  {{-- jeton de sécurité obligatoire pour tout formulaire POST --}}
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" :value="qty">

        <h2 class="text-xl font-extrabold">{{ __('معلومات التوصيل') }}</h2>

        {{-- Erreurs de validation renvoyées par le controller --}}
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 rounded-xl p-3 text-sm">
                @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold mb-1">{{ __('الاسم الكامل') }}</label>
            <input name="customer_name" value="{{ old('customer_name') }}" required
                   class="w-full border border-stone-300 rounded-xl px-3 py-2 focus:outline-brand">
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">{{ __('رقم الهاتف') }}</label>
            <input name="phone" type="tel" value="{{ old('phone') }}" required pattern="0[5-7][0-9]{8}"
                   placeholder="0550123456" dir="ltr"
                   class="w-full border border-stone-300 rounded-xl px-3 py-2 text-start focus:outline-brand">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold mb-1">{{ __('الولاية') }}</label>
                <select name="wilaya_id" x-model="wilaya" required class="w-full border border-stone-300 rounded-xl px-3 py-2 bg-white">
                    <option value="">{{ __('اختر...') }}</option>
                    <template x-for="w in wilayas" :key="w.code">
                        <option :value="w.code" x-text="w.code + ' - ' + w.name" :selected="w.code == wilaya"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">{{ __('البلدية') }}</label>
                <input name="commune" value="{{ old('commune') }}" required class="w-full border border-stone-300 rounded-xl px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">{{ __('نوع التوصيل') }}</label>
            <div class="grid grid-cols-2 gap-3">
                <label class="border rounded-xl px-3 py-2 cursor-pointer text-center"
                       :class="type === 'home' ? 'border-brand bg-brand/5 font-bold' : 'border-stone-300'">
                    <input type="radio" name="delivery_type" value="home" x-model="type" class="sr-only"> {{ __('للمنزل') }}
                </label>
                <label class="border rounded-xl px-3 py-2 cursor-pointer text-center"
                       :class="type === 'desk' ? 'border-brand bg-brand/5 font-bold' : 'border-stone-300'">
                    <input type="radio" name="delivery_type" value="desk" x-model="type" class="sr-only"> Stop desk
                </label>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <span class="text-sm font-bold">{{ __('الكمية') }}</span>
            <div class="flex items-center border border-stone-300 rounded-xl">
                <button type="button" @click="qty < stock && qty++" class="px-3 py-1 text-lg">+</button>
                <span x-text="qty" class="w-8 text-center font-bold"></span>
                <button type="button" @click="qty > 1 && qty--" class="px-3 py-1 text-lg">−</button>
            </div>
        </div>

        {{-- Récapitulatif (indicatif : le vrai total est recalculé par le serveur) --}}
        <div class="bg-cream rounded-xl p-4 space-y-1 text-sm">
            <div class="flex justify-between"><span>{{ __('المنتوج') }}</span>
                <span><span dir="ltr" x-text="money(price * qty)"></span> {{ __('دج') }}</span></div>
            <div class="flex justify-between"><span>{{ __('التوصيل') }}</span>
                <span x-show="!wilaya">{{ __('اختر الولاية') }}</span>
                <span x-show="wilaya" x-cloak><span dir="ltr" x-text="money(shipping)"></span> {{ __('دج') }}</span></div>
            <div class="flex justify-between font-extrabold text-lg pt-2 border-t border-stone-300">
                <span>{{ __('المجموع') }}</span><span class="text-brand"><span dir="ltr" x-text="money(total)"></span> {{ __('دج') }}</span></div>
        </div>

        <button class="w-full bg-brand hover:bg-brand-dark text-white font-extrabold py-3 rounded-xl transition">
            {{ __('تأكيد الطلب · الدفع عند الاستلام') }}
        </button>
    </form>
    @endif
</div>
@endsection
