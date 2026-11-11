@extends('layouts.admin')

@section('title', 'Товары — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ</span><h1>Товары</h1><p>Добавление и редактирование товаров, цен и наличия.</p></div><a class="btn btn-primary-soft" href="{{ route('admin.products.create') }}">+ Добавить товар</a></div>
    <section class="admin-panel">
        <form class="admin-search mb-3" method="GET"><input class="form-control" name="q" value="{{ request('q') }}" placeholder="Найти товар по названию"><button class="btn btn-outline-purple">Найти</button></form>
        <div class="table-responsive"><table class="table admin-table"><thead><tr><th>Товар</th><th>Категория</th><th>Бренд</th><th>Цена</th><th>Остаток</th><th>Метки</th><th></th></tr></thead><tbody>
        @forelse($products as $product)<tr><td><strong>{{ $product->name }}</strong><small class="d-block text-muted">{{ $product->slug }}</small></td><td>{{ $product->category?->name ?? '—' }}</td><td>{{ $product->brand?->name ?? '—' }}</td><td>{{ number_format((float) $product->current_price, 0, ',', ' ') }} ₽</td><td>{{ $product->stock }}</td><td>@if($product->sale_price)<span class="admin-status">Акция</span>@endif @if($product->is_new)<span class="admin-status">Новинка</span>@endif @if($product->is_featured)<span class="admin-status">Популярный</span>@endif</td><td><div class="admin-actions"><a href="{{ route('admin.products.edit', $product) }}">Изменить</a><form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Удалить товар?')">@csrf @method('DELETE')<button>Удалить</button></form></div></td></tr>@empty<tr><td colspan="7" class="text-center text-muted py-4">Товары не найдены</td></tr>@endforelse
        </tbody></table></div>{{ $products->links() }}
    </section>
@endsection
