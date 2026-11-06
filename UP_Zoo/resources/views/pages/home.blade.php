@extends('layouts.app')

@section('title', 'UP_ZOO — забота в каждом хвостике')

@section('content')
    <section class="hero-section">
        <div class="container">
            <div class="hero-card">
                <div class="hero-copy">
                    <span class="eyebrow">ЛЮБИМ ПИТОМЦЕВ, КАК СВОИХ</span>
                    <h1>Счастливый хвост —<br><span>счастливый дом.</span></h1>
                    <p>Всё самое нужное для маленьких и больших друзей — с заботой, вниманием и любовью.</p>
                    <div class="d-flex flex-wrap gap-3"><a class="btn btn-primary-soft" href="{{ route('catalog') }}">Перейти в каталог <span>→</span></a><a class="hero-secondary" href="{{ route('about') }}">Узнать о нас</a></div>
                    <div class="hero-note"><span class="hero-note-icon">♡</span><span>Бережно выбираем<br>каждый товар</span></div>
                </div>
                <div class="hero-art" aria-hidden="true"><div class="hero-sun"></div><div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div><div class="hero-pet">🐕</div><div class="hero-sparkle sparkle-one">✦</div><div class="hero-sparkle sparkle-two">✿</div><div class="hero-float-card">С любовью<br>для хвостиков <span>♡</span></div></div>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="container">
            <div class="section-heading"><div><span class="eyebrow">НАЙДЁТСЯ ВСЁ</span><h2>Кому сегодня выбираем?</h2></div><a class="text-link" href="{{ route('catalog') }}">Весь каталог <span>→</span></a></div>
            <div class="row g-3">
                @foreach ($categories as $category)
                    <div class="col-6 col-lg-3"><a class="category-tile" href="{{ route('catalog', ['category' => $category->slug]) }}"><span class="category-icon">{{ $category->icon }}</span><span><strong>{{ $category->name }}</strong><small>{{ $category->products_count }} товаров</small></span><span class="category-arrow">↗</span></a></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-block section-tint">
        <div class="container">
            <div class="section-heading"><div><span class="eyebrow">ВЫБРАЛИ С ЛЮБОВЬЮ</span><h2>Любимчики UP_ZOO</h2></div><a class="text-link" href="{{ route('catalog') }}">Смотреть всё <span>→</span></a></div>
            <div class="row g-4">@forelse ($products as $product)<div class="col-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>@empty<div class="col-12"><p>Скоро здесь появятся наши любимые товары.</p></div>@endforelse</div>
        </div>
    </section>

    <section class="section-block">
        <div class="container"><div class="promo-banner"><div><span class="eyebrow">ПЕРВАЯ ПОКУПКА — С ОСОБОЙ ЗАБОТОЙ</span><h2>Порадуйте своего хвостика</h2><p>Загляните в каталог: мы уже собрали много полезного.</p><a class="btn btn-light rounded-pill px-4" href="{{ route('promotions') }}">Посмотреть акции <span>→</span></a></div><div class="promo-illustration" aria-hidden="true">🎁</div></div></div>
    </section>

    <section class="section-block section-tint">
        <div class="container">
            <div class="section-heading"><div><span class="eyebrow">ОТ ВСЕГО СЕРДЦА</span><h2>Питомцы одобряют</h2></div><a class="text-link" href="{{ route('reviews.index') }}">Все отзывы <span>→</span></a></div>
            <div class="row g-4">@foreach ($reviews as $review)<div class="col-md-4"><article class="review-card"><div class="review-stars">{{ str_repeat('★', $review->rating) }}</div><p>«{{ $review->text }}»</p><strong>{{ $review->author_name }}</strong><small>Покупатель UP_ZOO</small></article></div>@endforeach</div>
        </div>
    </section>

    <section class="section-block"><div class="container"><div class="about-strip"><span class="about-strip-icon">🐾</span><div><span class="eyebrow">UP_ZOO — ЭТО ПРО ЗАБОТУ</span><h2>Питомцы — семья. Мы это понимаем.</h2><p>Помогаем выбирать лучшее для тех, кто каждый день дарит нам безусловную любовь.</p></div><a class="btn btn-outline-purple rounded-pill px-4" href="{{ route('about') }}">О магазине</a></div></div></section>
@endsection
