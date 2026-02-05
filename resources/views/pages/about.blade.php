@extends('layouts.app')

@section('title', 'О нас | VR-Shop')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="h3 fw-bold mb-3">VR-Shop — команда, которая любит VR</h1>
                <p class="text-muted">Мы тестируем каждую модель перед добавлением в каталог, чтобы вы получали актуальные рекомендации. Помогаем с подбором под игры, обучение, симуляторы и бизнес-презентации.</p>
                <ul class="list-unstyled text-muted">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Работаем с 2020 года, знаем рынок и новинки</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Даем честные консультации и видеоинструкции</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Поддержка после покупки: настройка, обновления, сервис</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="ratio ratio-16x9 rounded overflow-hidden shadow">
                    <img src="{{ asset('storage/img/about-vr.jpg') }}" class="w-100 h-100 object-fit-cover" alt="VR команда">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Тесты и обзоры</h5>
                        <p class="text-muted mb-0">Записываем короткие обзоры на YouTube и делимся оптимальными настройками для Oculus, HTC, PICO и других систем.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Подбор под задачу</h5>
                        <p class="text-muted mb-0">Игры, тренажёры, архитектура или обучение — соберём комплект под конкретный сценарий и бюджет.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Поддержка</h5>
                        <p class="text-muted mb-0">Чаты с техподдержкой, инструкции и помощь с обновлениями прошивки, драйверов и библиотек.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
