@extends('layouts.app')

@section('title', 'Корзина — UP_ZOO')

@section('content')
    <section class="page-hero"><div class="container"><span class="eyebrow">УЖЕ ПОЧТИ ДОМА</span><h1>Корзина</h1><p>Проверьте покупки, а мы бережно всё соберём.</p></div></section>
    <section class="section-block"><div class="container">
        @if ($items->isEmpty())
            <div class="empty-state"><span>🧺</span><h2>Корзина пока пустая</h2><p>Здесь будут товары, которые вы выбрали для своего питомца.</p><a class="btn btn-primary-soft" href="{{ route('catalog') }}">Перейти в каталог <span>→</span></a></div>
        @else
            <div class="row g-4"><div class="col-lg-8">@foreach ($items as $item)<div class="cart-item"><div class="cart-product-icon">{{ $item['product']->category?->icon ?? '🐾' }}</div><div class="cart-product-info"><a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a><span>{{ number_format((float) $item['product']->current_price, 0, ',', ' ') }} ₽ за шт.</span></div><form action="{{ route('cart.update', $item['product']) }}" method="POST" class="d-flex align-items-center gap-2">@csrf @method('PATCH')<label class="visually-hidden" for="quantity-{{ $item['product']->id }}">Количество</label><input class="form-control quantity-input" id="quantity-{{ $item['product']->id }}" type="number" name="quantity" min="1" max="{{ min($item['product']->stock, 99) }}" value="{{ $item['quantity'] }}"><button class="btn btn-outline-purple btn-sm" type="submit">Обновить</button></form><strong class="cart-line-total">{{ number_format((float) $item['product']->current_price * $item['quantity'], 0, ',', ' ') }} ₽</strong><form action="{{ route('cart.destroy', $item['product']) }}" method="POST">@csrf @method('DELETE')<button class="remove-link" type="submit" aria-label="Удалить товар">×</button></form></div>@endforeach</div>
                <div class="col-lg-4"><div class="order-summary"><h2>Ваш заказ</h2><div><span>Товары</span><strong>{{ number_format($total, 0, ',', ' ') }} ₽</strong></div><div><span>Доставка</span><strong>Рассчитаем при подтверждении</strong></div><hr><div class="summary-total"><span>Итого</span><strong>{{ number_format($total, 0, ',', ' ') }} ₽</strong></div><a href="{{ route('checkout.create') }}" class="btn btn-primary-soft w-100 mt-3">Оформить заказ <span>→</span></a><a href="{{ route('catalog') }}" class="text-link d-block text-center mt-3">Продолжить покупки</a></div></div>
            </div>
        @endif
    </div></section>
@endsection
