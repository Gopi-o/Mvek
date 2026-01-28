@extends('layouts.app')

@section('title', 'Оформление заказа')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pb-0">
                    <h3 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-credit-card me-2 text-success"></i>
                        Оформление заказа
                    </h3>
                </div>
                
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Товар</th>
                                        <th class="border-0 text-center">Кол-во</th>
                                        <th class="border-0 text-end">Цена</th>
                                        <th class="border-0 text-end">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                    @php
                                        $product = $products->firstWhere('id', $id);
                                    @endphp
                                    @if($product)
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light rounded-circle p-2" style="width: 50px; height: 50px;">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/img/' . $product->image) }}" 
                                                             class="w-100 h-100 rounded-circle object-fit-cover" alt="{{ $product->name }}">
                                                    @else
                                                        <i class="fas fa-vr-cardboard text-primary fs-5"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ $product->category->name ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-success fs-6">{{ $item['quantity'] }}</span>
                                        </td>
                                        <td class="align-middle text-end">
                                            {{ number_format($product->price, 0, ',', ' ') }} ₽
                                        </td>
                                        <td class="align-middle text-end fw-bold text-success">
                                            {{ number_format($product->price * $item['quantity'], 0, ',', ' ') }} ₽
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-4 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 fw-bold text-dark">Итого:</span>
                            <span class="h3 text-danger fw-bold mb-0">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Контактные данные</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Имя *</label>
                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" required>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Телефон *</label>
                                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" required>
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror">
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Доставка</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Город *</label>
                                            <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Адрес *</label>
                                            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                                   placeholder="ул. Ленина, д. 5, кв. 12" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="self-delivery" name="delivery" value="self">
                                                <label class="form-check-label" for="self-delivery">
                                                    Самовывоз (бесплатно)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-success btn-lg py-3 fs-5 fw-bold shadow-lg">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Оформить заказ на {{ number_format($total, 0, ',', ' ') }} ₽
                                </button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary py-2">
                                    <i class="fas fa-arrow-left me-2"></i>Назад к корзине
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.object-fit-cover { object-fit: cover; }
.table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
@endpush
