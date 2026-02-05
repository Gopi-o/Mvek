@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', $product->name . ' — подробное описание')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm bg-white">
                    <img src="{{ $product->image ? asset('storage/img/' . $product->image) : asset('storage/img/vr-ochki-def.jpg')}}" 
                         class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                    @if($product->stock > 0)
                        <span class="text-success fw-semibold">{{ $product->stock }} шт. в наличии</span>
                    @else
                        <span class="badge bg-danger">Нет в наличии</span>
                    @endif
                </div>
                <h1 class="h3 fw-bold mb-3">{{ $product->name }}</h1>
                <p class="text-muted fs-6">{{ $product->description }}</p>

                <div class="d-flex align-items-center gap-3 my-4">
                    <div class="display-6 text-danger fw-bold mb-0">
                        {{ number_format($product->price, 0, ',', ' ') }} ₽
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if($product->stock > 0)
                        <button class="btn btn-success" onclick="addToCart({{ $product->id }})">
                            <i class="fas fa-cart-plus me-2"></i>В корзину
                        </button>
                    @endif

                    <form method="POST" action="{{ route('compare.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-balance-scale me-2"></i>Добавить в сравнение
                        </button>
                    </form>

                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к каталогу
                    </a>
                </div>

                <div class="mt-4">
                    <h5 class="fw-semibold">Почему стоит выбрать</h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Гарантия 12 месяцев и быстрая доставка</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Поддержка популярных платформ и игр</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Подробные инструкции и помощь в настройке</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@if($related->count())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Похожие товары</h2>
            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="text-decoration-none">Смотреть все</a>
        </div>
        <div class="row g-4">
            @foreach($related as $item)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 180px;">
                        <img src="{{ $item->image ? asset('storage/img/' . $item->image) : asset('storage/img/vr-ochki-def.jpg')}}" 
                             class="w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title text-truncate mb-2">{{ $item->name }}</h6>
                        <p class="text-muted small mb-3">{{ Str::limit($item->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold text-danger">{{ number_format($item->price, 0, ',', ' ') }} ₽</span>
                            <a href="{{ route('products.show', $item) }}" class="btn btn-outline-primary btn-sm">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
