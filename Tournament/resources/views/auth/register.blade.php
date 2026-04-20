@extends('layouts.app')

@section('title', 'Регистрация — Tournament.GGs')

@section('content')
<section class="py-5" style="background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%); border-bottom: 1px solid var(--border); min-height: calc(100vh - 73px); display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="text-center mb-5">
                    <span class="d-block mb-3" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ РЕГИСТРАЦИЯ ]</span>
                    <h1 style="font-size: 2rem; font-weight: 800; letter-spacing: -0.02em;">НОВЫЙ АККАУНТ</h1>
                </div>

                <div class="card" style="border: 1px solid var(--border);">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label">ИМЯ</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Игровой ник или имя"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">EMAIL</label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       placeholder="user@example.com"
                                       value="{{ old('email') }}"
                                       required>
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
                                       placeholder="Минимум 6 символов"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">ПОДТВЕРЖДЕНИЕ ПАРОЛЯ</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control" 
                                       placeholder="Повторите пароль"
                                       required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">СОЗДАТЬ АККАУНТ</button>

                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--text-muted); font-size: 0.8rem;">
                                    Уже есть аккаунт? <span style="color: var(--accent-orange);">Войти</span>
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