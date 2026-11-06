@extends('layouts.app')

@section('title', 'Войти — UP_ZOO')

@section('content')
    <section class="auth-section"><div class="auth-card"><span class="auth-icon">🐾</span><span class="eyebrow">С ВОЗВРАЩЕНИЕМ</span><h1>Войти в кабинет</h1><p>Ваш питомец уже ждёт маленький сюрприз.</p><form action="{{ route('login.store') }}" method="POST" class="row g-3 text-start">@csrf<div class="col-12"><label class="form-label" for="email">Электронная почта</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></div><div class="col-12"><label class="form-label" for="password">Пароль</label><input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required></div><div class="col-12 form-check ms-2"><input class="form-check-input" id="remember" name="remember" type="checkbox" value="1"><label class="form-check-label" for="remember">Запомнить меня</label></div><div class="col-12"><button class="btn btn-primary-soft w-100" type="submit">Войти <span>→</span></button></div></form><div class="auth-bottom">Впервые у нас? <a href="{{ route('register') }}">Создать аккаунт</a></div></div></section>
@endsection
