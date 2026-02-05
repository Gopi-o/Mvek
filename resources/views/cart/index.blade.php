@extends('layouts.app')
@section('title', 'Корзина')

@section('content')
<div class="container py-5">
    <h1>Корзина покупок</h1>
    
    @if(!empty($cart))
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Кол-во</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        @php $product = $products->firstWhere('id', $id) @endphp
                        @if($product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td class="fw-bold">{{ number_format($product->price * $item['quantity'], 0, ',', ' ') }} ₽</td>
                            <td>
                                <button onclick="removeFromCart('{{ $id }}')" 
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="text-end">
            <h3>Итого: {{ number_format($total, 0, ',', ' ') }} ₽</h3>
            <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg">Оформить заказ</a>
            <button type="button" class="btn btn-outline-danger" onclick="clearCartPage()">Очистить</button>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h4>Корзина пуста</h4>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary">В каталог</a>
        </div>
    @endif
</div>


@endsection

@push('scripts')
<script>
function clearCartPage() {
    if (!confirm('Очистить корзину?')) return;
    fetch('{{ route("cart.clear") }}', {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
    })
    .then(r => r.json())
    .then(() => location.reload())
    .catch(() => alert('Ошибка'));
}

function removeFromCart(productId) {
    if (!confirm('Удалить товар из корзины?')) return;
    
    fetch('{{ route("cart.remove") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
            showToast(data.message);
        }
    })
    .catch(e => showToast('Ошибка удаления'));
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.innerHTML = `<div class="alert alert-success">${msg}</div>`;
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;width:300px;';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>


