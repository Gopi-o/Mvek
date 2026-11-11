@extends('layouts.admin')

@section('title', 'Обзор — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">UP_ZOO / ПАНЕЛЬ УПРАВЛЕНИЯ</span><h1>Обзор магазина</h1><p>Добро пожаловать, {{ auth()->user()->name }}. Здесь собраны основные показатели магазина.</p></div></div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Товары в каталоге</span><strong>{{ $productCount }}</strong><small>позиций</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Всего заказов</span><strong>{{ $orderCount }}</strong><small>заказов</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Новые заказы</span><strong>{{ $newOrderCount }}</strong><small>требуют внимания</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Покупатели</span><strong>{{ $userCount }}</strong><small>аккаунтов</small></div></div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8"><section class="admin-panel"><div class="admin-panel-heading"><h2>Последние заказы</h2><a href="{{ route('admin.orders.index') }}">Все заказы →</a></div><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Заказ</th><th>Покупатель</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr></thead><tbody>@forelse($orders as $order)<tr><td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td><td>{{ $order->customer_name }}</td><td>{{ $order->created_at->format('d.m.Y') }}</td><td>{{ number_format((float) $order->total, 0, ',', ' ') }} ₽</td><td><span class="admin-status">{{ $order->status }}</span></td></tr>@empty<tr><td colspan="5" class="text-center text-muted py-4">Заказов пока нет</td></tr>@endforelse</tbody></table></div></section></div>
        <div class="col-lg-4"><section class="admin-panel admin-review-alert"><span class="eyebrow">ОТЗЫВЫ</span><strong>{{ $pendingReviewCount }}</strong><p>отзывов ожидают модерации</p><a class="btn btn-primary-soft" href="{{ route('admin.reviews.index') }}">Перейти к отзывам →</a></section></div>
    </div>
@endsection
