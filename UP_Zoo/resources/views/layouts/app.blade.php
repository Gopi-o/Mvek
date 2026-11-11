<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7c1ca6">
    <title>@yield('title', 'UP_ZOO — забота в каждом хвостике')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/upzoo.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="topline">Бережная доставка для хвостиков — от 2 000 ₽ бесплатно</div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container">
            <a class="navbar-brand brand-mark" href="{{ route('home') }}"><span class="brand-paw">✿</span> UP_<span>ZOO</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Открыть меню">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <div class="navbar-nav mx-auto">
                    <a class="nav-link" href="{{ route('about') }}">О магазине</a>
                    <a class="nav-link" href="{{ route('catalog') }}">Каталог</a>
                    <a class="nav-link" href="{{ route('promotions') }}">Акции</a>
                    <a class="nav-link" href="{{ route('new') }}">Новинки</a>
                    <a class="nav-link" href="{{ route('brands') }}">Бренды</a>
                    <a class="nav-link" href="{{ route('reviews.index') }}">Отзывы</a>
                    <a class="nav-link" href="{{ route('contacts') }}">Контакты</a>
                </div>
                <div class="nav-actions">
                    <a class="nav-icon-link" href="{{ route('cart.index') }}" aria-label="Корзина">Корзина <span class="cart-count">{{ array_sum(session('cart', [])) }}</span></a>
                    @auth
                        <a class="nav-icon-link" href="{{ route('account') }}">Кабинет</a>
                        @if (auth()->user()->hasAnyRole(['admin', 'manager']))
                            <a class="nav-icon-link" href="{{ route('admin.dashboard') }}">Панель</a>
                        @endif
                    @else
                        <a class="nav-icon-link" href="{{ route('login') }}">Войти</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main>
        @if (session('success'))
            <div class="container pt-3"><div class="alert alert-success alert-dismissible fade show mb-0" role="status">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button></div></div>
        @endif
        @if ($errors->any())
            <div class="container pt-3"><div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button></div></div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row g-4 py-5">
                <div class="col-lg-4">
                    <a class="navbar-brand brand-mark text-white" href="{{ route('home') }}"><span class="brand-paw">✿</span> UP_<span>ZOO</span></a>
                    <p class="footer-copy mt-3">Маленькие радости для тех, кто делает наш дом теплее.</p>
                </div>
                <div class="col-6 col-lg-2"><h2 class="footer-heading">Магазин</h2><a href="{{ route('about') }}">О нас</a><a href="{{ route('catalog') }}">Каталог</a><a href="{{ route('promotions') }}">Акции</a><a href="{{ route('brands') }}">Бренды</a></div>
                <div class="col-6 col-lg-2"><h2 class="footer-heading">Покупателям</h2><a href="{{ route('reviews.index') }}">Отзывы</a><a href="{{ route('contacts') }}">Контакты</a><a href="{{ route('cart.index') }}">Корзина</a>@auth<a href="{{ route('account') }}">Личный кабинет</a>@else<a href="{{ route('login') }}">Войти</a>@endauth</div>
                <div class="col-lg-4"><h2 class="footer-heading">Всегда на связи</h2><a href="tel:+78005553535">8 800 555-35-35</a><a href="mailto:hello@upzoo.ru">hello@upzoo.ru</a><p class="footer-copy mt-2">Ежедневно, 9:00–21:00</p></div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2"><span>© {{ date('Y') }} UP_ZOO. Забота в каждом хвостике.</span><span>Цены указаны в рублях. Информация на сайте носит ознакомительный характер.</span></div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
