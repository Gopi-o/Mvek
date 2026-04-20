<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tournament.GGs')</title>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Tournament.GGs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('tournaments.index') }}">Турниры</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('participants.index') }}">Участники</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('faq') }}">FAQ</a></li>
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Вход</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}">Профиль</a></li>
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Админка</a></li>
                        @endif
                        <li class="nav-item"><span class="nav-link">{{ Auth::user()->name }}</span></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Выход</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>