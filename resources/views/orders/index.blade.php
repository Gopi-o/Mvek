@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list me-2"></i>Мои заказы</h2>
        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart me-1"></i>Корзина
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row g-4">
            @foreach($orders as $order)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-{{ $order->status == 'new' ? 'warning' : 'success' }}">
                                    {{ $order->status == 'new' ? 'Новый' : 'Выполнен' }}
                                </span>
                                <span class="fw-bold text-danger fs-6">{{ $order->total }} ₽</span>
                            </div>
                            <h6 class="card-title mb-3">#{{ $order->id }}</h6>
                            <p class="small text-muted mb-2"><i class="fas fa-user me-1"></i>{{ $order->name }}</p>
                            <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i>{{ $order->city }}</p>
                            <p class="small text-muted mb-3"><i class="fas fa-phone me-1"></i>{{ $order->phone }}</p>
                            <small class="text-muted">{{ $order->created_at->format('d.m.Y H:i') }}</small>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm w-100">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-4 opacity-50"></i>
            <h5 class="text-muted mb-3">У вас пока нет заказов</h5>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Перейти в каталог
            </a>
        </div>
    @endif
</div>
@endsection
