@extends('layouts.app')

@section('title', $title.' — UP_ZOO')

@section('content')
    <section class="page-hero"><div class="container"><span class="eyebrow">ПОДБРАНО С ЗАБОТОЙ</span><h1>{{ $title }}</h1><p>{{ $subtitle }}</p></div></section>
    <section class="section-block"><div class="container">
        @if (isset($categories))
            <form class="filter-panel row g-3 align-items-end mb-4" method="GET" action="{{ route('catalog') }}">
                <div class="col-12 col-md-4"><label class="form-label" for="search">Поиск</label><input class="form-control" id="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Например, корм"></div>
                <div class="col-6 col-md-2"><label class="form-label" for="category">Категория</label><select class="form-select" id="category" name="category"><option value="">Все</option>@foreach ($categories as $category)<option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>@endforeach</select></div>
                <div class="col-6 col-md-2"><label class="form-label" for="brand">Бренд</label><select class="form-select" id="brand" name="brand"><option value="">Все</option>@foreach ($brands as $brand)<option value="{{ $brand->slug }}" @selected(($filters['brand'] ?? '') === $brand->slug)>{{ $brand->name }}</option>@endforeach</select></div>
                <div class="col-6 col-md-1"><label class="form-label" for="min_price">От, ₽</label><input class="form-control" id="min_price" type="number" min="0" name="min_price" value="{{ $filters['min_price'] ?? '' }}"></div>
                <div class="col-6 col-md-1"><label class="form-label" for="max_price">До, ₽</label><input class="form-control" id="max_price" type="number" min="0" name="max_price" value="{{ $filters['max_price'] ?? '' }}"></div>
                <div class="col-12 col-md-2"><button class="btn btn-primary-soft w-100" type="submit">Найти товары</button></div>
            </form>
        @endif
        <div class="row g-4">@forelse ($products as $product)<div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>@empty<div class="col-12"><div class="empty-state"><span>🐾</span><h2>Пока ничего не нашлось</h2><p>Попробуйте изменить условия поиска или загляните позже.</p><a href="{{ route('catalog') }}" class="btn btn-outline-purple rounded-pill">Сбросить фильтры</a></div></div>@endforelse</div>
        <div class="mt-4">{{ $products->links() }}</div>
    </div></section>
@endsection
