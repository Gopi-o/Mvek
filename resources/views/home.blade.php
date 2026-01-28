@extends('layouts.app')

@section('title', 'VR-очки и гаджеты | Лучшие VR-шлемы 2025')

@section('content')
<!-- Hero Section -->
<section class="bg-dark text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">VR-очки будущего</h1>
                <p class="lead mb-4">Погрузитесь в виртуальную реальность с лучшими VR-шлемами 2025 года. Oculus, HTC Vive, PICO — всё в наличии!</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg me-3">Каталог товаров</a>
                <a href="{{ route('compare.index') }}" class="btn btn-outline-light btn-lg">Сравнить модели</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('storage/img/home-badge-hero.jpg')}}" 
                     class="img-fluid rounded shadow-lg" alt="VR-очки">
            </div>
        </div>
    </div>
</section>

<!-- Новинки -->
<section class="py-5">
    <div class="container">
        <h2 class="h3 text-center mb-5">Новинки недели</h2>
        <div class="row g-4">
            @forelse($featured as $product)
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 200px;">
                        <img src="{{ $product->image ? asset('storage/img/' . $product->image) : asset('storage/img/vr-ochki-def.jpg')}}" 
                            class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                        <span class="badge bg-success position-absolute top-0 start-0 m-2">Новинка</span>
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                        <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 70) }}</p>
                        <div class="d-flex justify-content-between align-items-end flex-grow-1">
                            <div>
                                <span class="h5 text-danger fw-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                                <br><small class="text-muted">{{ $product->stock }} шт. в наличии</small>
                            </div>
                            <form method="POST" action="{{ route('compare.add') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-outline-primary btn-sm">Сравнить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h4>Новинок пока нет</h4>
                <p>Добавьте товары через админ-панель</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Категории -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="h3 text-center mb-5">Популярные категории</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-4 col-lg-2">
                <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <div class="card border-0 h-100 text-center hover-shadow">
                        <div class="card-body py-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-block p-4 mb-3">
                                <i class="fas fa-vr-cardboard fs-1 text-primary"></i>
                            </div>
                            <h6 class="fw-bold mb-2">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products->count() }} товаров</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.hover-shadow { transition: all 0.3s; }
.hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important; }
.object-fit-cover { object-fit: cover; }
</style>
@endsection
