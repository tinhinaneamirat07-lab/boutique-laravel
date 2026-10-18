@extends('layouts.app')

@section('title', __('شكراً'))

@section('content')
<div class="max-w-md mx-auto bg-white border border-stone-200 rounded-2xl p-8 text-center">
    <div class="w-16 h-16 mx-auto rounded-full bg-green-100 text-green-700 text-3xl flex items-center justify-center">✓</div>
    <h1 class="text-2xl font-extrabold mt-4">{{ __('شكراً') }} <bdi>{{ $order->customer_name }}</bdi>!</h1>
    <p class="text-stone-600 mt-2">
        {{ __('تم تسجيل طلبك رقم') }} <b>#{{ $order->id }}</b>.<br>
        {{ __('سنتصل بك قريباً لتأكيد الطلب على') }} <b dir="ltr">{{ $order->phone }}</b>
    </p>

    <div class="bg-cream rounded-xl p-4 mt-6 text-sm space-y-1 text-start">
        <div class="flex justify-between"><span>{{ $order->product->display_name }} × {{ $order->quantity }}</span>
            <span><span dir="ltr">{{ number_format($order->unit_price * $order->quantity, 0, ',', ' ') }}</span> {{ __('دج') }}</span></div>
        <div class="flex justify-between"><span>{{ __('التوصيل') }} ({{ $order->wilaya->display_name }})</span>
            <span><span dir="ltr">{{ number_format($order->shipping_price, 0, ',', ' ') }}</span> {{ __('دج') }}</span></div>
        <div class="flex justify-between font-extrabold pt-2 border-t border-stone-300"><span>{{ __('المجموع') }}</span>
            <span class="text-brand"><span dir="ltr">{{ number_format($order->total, 0, ',', ' ') }}</span> {{ __('دج') }}</span></div>
    </div>

    <a href="{{ route('products.index') }}" class="inline-block mt-6 text-brand font-bold">{{ __('← العودة للمنتوجات') }}</a>
</div>
@endsection
