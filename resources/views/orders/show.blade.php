@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0 py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-1 fw-bold">
                                <i class="fas fa-check-circle me-2"></i>
                                Заказ #{{ $order->id }}
                            </h2>
                            <div class="badge bg-{{ $order->status == 'new' ? 'warning' : 'success' }} fs-6 px-3 py-2">
                                {{ $order->status == 'new' ? 'Новый' : 'Подтвержден' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="h4 fw-bold text-white mb-0">
                                {{ number_format($order->total, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-list me-2"></i>Товары
                        </h5>
                        <div class="row g-3">
                            @foreach($order->items as $item)
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                    <div class="bg-white rounded-circle p-2 shadow-sm" style="width: 60px; height: 60px;">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/img/' . $item->product->image) }}" 
                                                 class="w-100 h-100 rounded-circle object-fit-cover" alt="{{ $item->product->name }}">
                                        @else
                                            <i class="fas fa-vr-cardboard text-primary fs-4"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold">{{ $item->product->name }}</h6>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="badge bg-success">{{ $item->quantity }} шт.</span>
                                            <span>{{ $item->product->category->name ?? '' }}</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 fw-bold text-success mb-0">
                                            {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽
                                        </div>
                                        <small class="text-muted">{{ number_format($item->price, 0, ',', ' ') }} ₽ / шт.</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-0">
                        <div class="col-md-6 p-4 bg-light border-end">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-user me-2"></i>Покупатель
                            </h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <strong>Имя:</strong><br>
                                    {{ $order->name }}
                                </div>
                                <div class="col-6">
                                    <strong>Телефон:</strong><br>
                                    {{ $order->phone }}
                                </div>
                                @if($order->email)
                                <div class="col-12">
                                    <strong>Email:</strong><br>
                                    {{ $order->email }}
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-map-marker-alt me-2"></i>Доставка
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <strong>{{ $order->delivery == 'self' ? 'Самовывоз' : 'Доставка' }}:</strong><br>
                                    {{ $order->city }}<br>
                                    {{ $order->address }}
                                </div>
                                <div class="col-12">
                                    <strong>Дата:</strong><br>
                                    <span class="text-muted">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Общая сумма:</span>
                            <span class="h3 fw-bold mb-0">{{ number_format($order->total, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pt-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-primary py-2">
                            <i class="fas fa-shopping-bag me-2"></i>Продолжить покупки
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-shopping-cart me-2"></i>В корзину
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.object-fit-cover { object-fit: cover; }
</style>
@endpush
