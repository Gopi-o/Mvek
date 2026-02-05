@extends('layouts.app')

@section('title', 'Доставка и оплата | VR-Shop')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <h1 class="h3 fw-bold mb-3">Доставка и оплата</h1>
                <p class="text-muted">Отправляем по всей России и странам СНГ. Товар страхуем, упаковываем в жесткий бокс, даём трек сразу после отправки.</p>
                <h6 class="text-uppercase text-muted mt-4">Способы доставки</h6>
                <ul class="text-muted">
                    <li>Курьером по Москве и СПб — 1–2 дня.</li>
                    <li>СДЭК/Boxberry в регионы — 3–7 дней.</li>
                    <li>Экспресс EMS — 1–3 дня в крупные города.</li>
                </ul>
                <h6 class="text-uppercase text-muted mt-4">Оплата</h6>
                <ul class="text-muted">
                    <li>Онлайн картой/СБП.</li>
                    <li>Безнал для юрлиц с договором.</li>
                    <li>Наличные курьеру (только Ижевск/Удмуртия/).</li>
                </ul>
                <div class="alert alert-info small mb-0">Каждая посылка застрахована. Проверяйте комплектацию при получении.</div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Возврат и гарантия</h5>
                        <p class="text-muted">14 дней на возврат, если товар не использовался и сохранена комплектация. Гарантия производителя + наша поддержка.</p>
                        <ul class="text-muted small mb-0">
                            <li>Меняем брак за наш счёт.</li>
                            <li>Помогаем с прошивками и настройками.</li>
                            <li>Подменный шлем на время ремонта (для Москвы/СПб).</li>
                        </ul>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">Вопросы?</h5>
                        <p class="text-muted small mb-3">Напишите, подскажем по срокам и наличию.</p>
                        <a class="btn btn-primary w-100 mb-2" href="mailto:info@vr-shop.ru">info@vr-shop.ru</a>
                        <div class="text-center text-muted small">Тел: +7 (900) 000-00-00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
