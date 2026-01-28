@extends('layouts.app')
@section('title', 'Каталог VR-очков')

@section('content')
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Поиск</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" placeholder="Oculus, HTC Vive...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Категория</label>
                <select name="category" class="form-select">
                    <option value="">Все категории</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->products_count }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Цена</label>
                <div class="row">
                    <div class="col-6">
                        <input type="number" name="min_price" class="form-control" 
                               value="{{ request('min_price') }}" placeholder="от">
                    </div>
                    <div class="col-6">
                        <input type="number" name="max_price" class="form-control" 
                               value="{{ request('max_price') }}" placeholder="до">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label">Сортировка</label>
                <select name="sort" class="form-select">
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Цена: по возрастанию</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Цена: по убыванию</option>
                    <option value="new" {{ request('sort') == 'new' ? 'selected' : '' }}>Новинки</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary me-2">Применить</button>
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary">Сбросить</a>
            </div>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Каталог товаров</h1>
            <span class="text-muted">Найдено: {{ $products->total() }} товаров</span>
        </div>

        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm hover-shadow border-0">
                        <div class="card-img-top position-relative" style="height: 220px;">
                            <img src="{{ $product->image ? asset('storage/img/' . $product->image) : asset('storage/img/vr-ochki-def.jpg')}}" 
                                 class="w-100 h-100 object-fit-cover" alt="{{ $product->name }}">
                            @if($product->stock == 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Нет в наличии</span>
                            @else
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    {{ $product->stock }} шт.
                                </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                            <h6 class="card-title mb-2">
                                <a href="#" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h6>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="mt-2 d-flex gap-2 justify-content-between align-items-center">
                                <div class="h4 text-danger fw-bold mb-0 flex-grow-1">
                                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                                </div>
                                
                                @if($product->stock > 0)
                                    <div class="d-flex gap-1">
                                        <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', '{{ $product->image }}', {{ $product->price }})" class="btn btn-success btn-sm">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                        
                                        <form method="POST" action="{{ route('compare.add') }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-2" title="Добавить в сравнение">
                                                <i class="fas fa-balance-scale"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-danger fs-6 px-3 py-2">Нет в наличии</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <nav class="mt-5">
                {{ $products->appends(request()->query())->links() }}
            </nav>
        @else
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-4"></i>
                <h4>Товары не найдены</h4>
                <p class="text-muted">Попробуйте изменить фильтры или поисковый запрос</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Показать все товары</a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
.hover-shadow { transition: all 0.3s ease; }
.hover-shadow:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important; }
.object-fit-cover { object-fit: cover; }
</style>
@endpush

@push('scripts')
<script>
</script>
@endpush
