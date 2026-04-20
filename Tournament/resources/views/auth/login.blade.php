@extends('layouts.app')

@section('title', 'Вход — Tournament.GGs')

@section('content')
<section class="py-5" style="background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%); border-bottom: 1px solid var(--border); min-height: calc(100vh - 73px); display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-5">
                    <span class="d-block mb-3" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ АВТОРИЗАЦИЯ ]</span>
                    <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -0.02em;">ВХОД</h1>
                </div>

                <div class="card" style="border: 1px solid var(--border);">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">EMAIL</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="user@example.com"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">ПАРОЛЬ</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="••••••••"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="remember" class="form-check-input" style="background-color: var(--bg-elevated); border-color: var(--border);">
                                    <label for="remember" class="form-check-label" style="color: var(--text-muted); font-size: 0.8rem;">Запомнить меня</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">ВОЙТИ</button>

                            <div class="text-center">
                                <a href="{{ route('register') }}" class="text-decoration-none" style="color: var(--text-muted); font-size: 0.8rem;">
                                    Нет аккаунта? <span style="color: var(--accent-orange);">Регистрация</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection