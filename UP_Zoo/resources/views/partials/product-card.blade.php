<article class="product-card h-100">
    <a class="product-visual" href="{{ route('products.show', $product) }}" aria-label="{{ $product->name }}">
        @if ($product->sale_price)<span class="product-badge">Выгодно</span>@elseif ($product->is_new)<span class="product-badge badge-new">Новинка</span>@endif
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <span class="product-emoji">{{ $product->category?->icon ?? '🐾' }}</span>
            <span class="product-visual-label">{{ $product->category?->name ?? 'Для питомцев' }}</span>
        @endif
    </a>
    <div class="product-info">
        <div class="product-meta">{{ $product->brand?->name ?? 'UP_ZOO' }}@if ($product->category) · {{ $product->category->name }}@endif</div>
        <a class="product-title" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
        <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
            <div class="product-price">{{ number_format((float) $product->current_price, 0, ',', ' ') }} ₽ @if ($product->sale_price)<del>{{ number_format((float) $product->price, 0, ',', ' ') }} ₽</del>@endif</div>
            @if ($product->stock > 0)
                <form action="{{ route('cart.store', $product) }}" method="POST">@csrf<button class="btn btn-add" type="submit" aria-label="Добавить в корзину">+</button></form>
            @else
                <span class="text-muted small">Нет в наличии</span>
            @endif
        </div>
    </div>
</article>
