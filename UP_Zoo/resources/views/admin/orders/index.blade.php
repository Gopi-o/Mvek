@extends('layouts.admin')

@section('title', 'Заказы — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">ЗАКАЗЫ</span><h1>Заказы покупателей</h1><p>Просмотр заказов и обработка их статусов.</p></div></div>
    <section class="admin-panel"><div class="table-responsive"><table class="table admin-table"><thead><tr><th>№</th><th>Покупатель</th><th>Контакты</th><th>Дата</th><th>Сумма</th><th>Статус</th><th></th></tr></thead><tbody>@forelse($orders as $order)<tr><td>#{{ $order->id }}</td><td>{{ $order->customer_name }}<small class="d-block text-muted">{{ $order->user_id ? 'Аккаунт' : 'Гостевой заказ' }}</small></td><td>{{ $order->email }}<small class="d-block text-muted">{{ $order->phone }}</small></td><td>{{ $order->created_at->format('d.m.Y H:i') }}</td><td>{{ number_format((float) $order->total, 0, ',', ' ') }} ₽</td><td><span class="admin-status">{{ $statuses[$order->status] ?? $order->status }}</span></td><td><a href="{{ route('admin.orders.show', $order) }}">Открыть →</a></td></tr>@empty<tr><td colspan="7" class="text-center text-muted py-4">Заказов пока нет</td></tr>@endforelse</tbody></table></div>{{ $orders->links() }}</section>
@endsection
