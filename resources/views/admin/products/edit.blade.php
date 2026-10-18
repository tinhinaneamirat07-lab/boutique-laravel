@extends('layouts.admin')
@section('title', 'تعديل المنتوج')
@section('content')
    <h1 class="text-2xl font-extrabold mb-6">تعديل: {{ $product->name }}</h1>
    @include('admin.products._form', ['action' => route('admin.products.update', $product)])
@endsection
