<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Панель управления — UP_ZOO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/upzoo.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
            <a class="brand-mark" href="{{ route('admin.dashboard') }}"><span class="brand-paw">✿</span> UP_<span>ZOO</span> <small>ПАНЕЛЬ УПРАВЛЕНИЯ</small></a>
            <div class="d-flex align-items-center gap-3"><span class="admin-user">{{ auth()->user()->name }} · {{ auth()->user()->role === 'admin' ? 'Администратор' : 'Менеджер' }}</span><a class="admin-back-link" href="{{ route('home') }}">На сайт ↗</a></div>
        </div>
    </header>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <span class="admin-nav-label">УПРАВЛЕНИЕ</span>
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])>Обзор</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Товары</a>
                <a href="{{ route('admin.categories.index') }}" @class(['active' => request()->routeIs('admin.categories.*')])>Категории</a>
                <a href="{{ route('admin.brands.index') }}" @class(['active' => request()->routeIs('admin.brands.*')])>Бренды</a>
            @endif
            <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])>Заказы</a>
            <a href="{{ route('admin.reviews.index') }}" @class(['active' => request()->routeIs('admin.reviews.*')])>Отзывы</a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.users.index') }}" @class(['active' => request()->routeIs('admin.users.*')])>Пользователи</a>
            @endif
            <div class="admin-sidebar-bottom"><a href="{{ route('account') }}">Личный кабинет</a><form action="{{ route('logout') }}" method="POST">@csrf<button type="submit">Выйти</button></form></div>
        </aside>
        <main class="admin-content">
            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
