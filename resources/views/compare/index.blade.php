@extends('layouts.app')
@section('title', 'Сравнение товаров')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Сравнение товаров</h1>
        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>В каталог
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Изображение</th>
                        <th>Модель</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Наличие</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80x80?text=VR' }}" 
                                 class="rounded" width="80" height="80" style="object-fit: cover;" alt="{{ $product->name }}">
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong><br>
                            <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <div class="h5 text-danger fw-bold mb-0">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                        </td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }} шт.</span>
                            @else
                                <span class="badge bg-danger">Нет в наличии</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('compare.remove') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Удалить из сравнения?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-balance-scale fa-4x text-muted mb-4"></i>
            <h4>Товары для сравнения</h4>
            <p class="text-muted mb-4">Добавьте товары из каталога для сравнения характеристик.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Перейти в каталог
            </a>
        </div>
    @endif
</div>
@endsection
