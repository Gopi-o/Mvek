@extends('layouts.app')

@section('title', 'Бренды — UP_ZOO')

@section('content')
    <section class="page-hero"><div class="container"><span class="eyebrow">МЫ ИХ ВЫБРАЛИ</span><h1>Любимые бренды</h1><p>Только те, кому доверяем заботу о ваших питомцах.</p></div></section>
    <section class="section-block"><div class="container"><div class="row g-3 mb-5">@foreach ($brands as $brand)<div class="col-6 col-lg-4"><a class="brand-tile @if(request('brand') === $brand->slug) selected @endif" href="{{ route('brands', ['brand' => $brand->slug]) }}"><span class="brand-tile-mark">✦</span><strong>{{ $brand->name }}</strong><span>{{ $brand->description }}</span><small>{{ $brand->products_count }} товаров</small></a></div>@endforeach</div>@if(request('brand'))<a href="{{ route('brands') }}" class="text-link d-inline-block mb-3">Все бренды ←</a>@endif<div class="row g-4">@forelse ($products as $product)<div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>@empty<div class="col-12"><p>Товары этого бренда скоро появятся.</p></div>@endforelse</div><div class="mt-4">{{ $products->links() }}</div></div></section>
@endsection
