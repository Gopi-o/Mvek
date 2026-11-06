@extends('layouts.app')

@section('title', $product->name.' — UP_ZOO')

@section('content')
    <section class="section-block"><div class="container"><div class="row g-5 align-items-center">
        <div class="col-lg-6"><div class="product-detail-visual">@if ($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}">@else<span>{{ $product->category?->icon ?? '🐾' }}</span>@endif</div></div>
        <div class="col-lg-6"><div class="product-meta">{{ $product->brand?->name }}@if ($product->category) · {{ $product->category->name }}@endif</div><h1 class="content-heading">{{ $product->name }}</h1><p class="content-copy">{{ $product->description }}</p><div class="detail-price">{{ number_format((float) $product->current_price, 0, ',', ' ') }} ₽ @if ($product->sale_price)<del>{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</del>@endif</div><p class="text-muted">{{ $product->stock > 0 ? 'Есть в наличии' : 'Нет в наличии' }}</p>
            @if ($product->stock > 0)<form action="{{ route('cart.store', $product) }}" method="POST" class="d-flex gap-2 align-items-center mt-4">@csrf<label class="visually-hidden" for="quantity">Количество</label><input class="form-control quantity-input" id="quantity" type="number" name="quantity" min="1" max="{{ min($product->stock, 99) }}" value="1"><button class="btn btn-primary-soft" type="submit">Добавить в корзину <span>→</span></button></form>@endif
        </div>
    </div>
    @if ($related->isNotEmpty())<div class="section-heading mt-5"><h2>Вам может понравиться</h2></div><div class="row g-4">@foreach ($related as $relatedProduct)<div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $relatedProduct])</div>@endforeach</div>@endif
    </div></section>
@endsection
