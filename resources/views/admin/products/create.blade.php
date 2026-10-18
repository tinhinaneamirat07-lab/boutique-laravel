@extends('layouts.admin')
@section('title', 'منتوج جديد')
@section('content')
    <h1 class="text-2xl font-extrabold mb-6">منتوج جديد</h1>
    @include('admin.products._form', ['action' => route('admin.products.store')])
@endsection
