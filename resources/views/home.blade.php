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
                        <h6 class="card-title text-truncate">
                            <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h6>
                        <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 70) }}</p>
                        <div class="d-flex justify-content-between align-items-end flex-grow-1">
                            <div>
                                <span class="h5 text-danger fw-bold">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                                <br><small class="text-muted">{{ $product->stock }} шт. в наличии</small>
                            </div>
                            <div class="d-flex flex-column align-items-end gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">Подробнее</a>
                                <form method="POST" action="{{ route('compare.add') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Сравнить</button>
                                </form>
                            </div>
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

<!-- Преимущества -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Подбор под задачу</h6>
                        <p class="text-muted small mb-0">Игры, обучение или бизнес — соберём комплект и дадим инструкцию.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Тестируем сами</h6>
                        <p class="text-muted small mb-0">Каждую модель проверяем: трекинг, удобство, дисплеи, прошивки.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Быстрая доставка</h6>
                        <p class="text-muted small mb-0">Курьером или СДЭК. Страхуем отправку и даём трек сразу.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Поддержка</h6>
                        <p class="text-muted small mb-0">Поможем настроить, обновить прошивку и подобрать игры.</p>
                    </div>
                </div>
            </div>
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

<!-- FAQ -->
<section id="faq" class="py-5">
    <div class="container">
        <h2 class="h4 text-center mb-4">Частые вопросы</h2>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100">
                    <h6 class="fw-bold">Нужен ли мощный ПК?</h6>
                    <p class="text-muted small mb-0">Для автономных моделей (Meta/PICO) не нужен. Для SteamVR подойдут ПК с RTX 3060 и выше для высоких настроек.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100">
                    <h6 class="fw-bold">Есть ли рассрочка?</h6>
                    <p class="text-muted small mb-0">Онлайн-оплата картой и СБП, рассрочку оформляем через партнерский банк по запросу.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100">
                    <h6 class="fw-bold">Поможете настроить?</h6>
                    <p class="text-muted small mb-0">Да, даём инструкции, видео и подключаемся удалённо при необходимости.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded-3 bg-light h-100">
                    <h6 class="fw-bold">Можно протестировать?</h6>
                    <p class="text-muted small mb-0">В Москве/СПб можно оформить демо-сессию по предварительной записи.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Контакты -->
<section id="contact" class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <h2 class="h4 mb-3">Нужна консультация по VR?</h2>
                <p class="text-white-50 mb-3">Оставьте заявку — подберём шлем и игры под ваш сценарий. Ответим в течение дня.</p>
                <a href="mailto:info@vr-shop.ru" class="btn btn-primary me-2">info@vr-shop.ru</a>
                <span class="text-white-50 small">Тел: +7 (900) 000-00-00 (10:00–20:00 МСК)</span>
            </div>
            <div class="col-lg-6">
                <div class="card bg-light border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">Что написать в запросе?</h6>
                        <ul class="text-muted small mb-0">
                            <li>Цель: игры, работа, обучение или бизнес.</li>
                            <li>Бюджет и желаемый срок доставки.</li>
                            <li>Есть ли ПК и его конфигурация.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hover-shadow { transition: all 0.3s; }
.hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important; }
.object-fit-cover { object-fit: cover; }
</style>
@endsection
