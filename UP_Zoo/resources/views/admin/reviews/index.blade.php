@extends('layouts.admin')

@section('title', 'Отзывы — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">ОТЗЫВЫ</span><h1>Отзывы покупателей</h1><p>{{ auth()->user()->role === 'admin' ? 'Проверяйте и публикуйте отзывы покупателей.' : 'Просмотр отзывов покупателей.' }}</p></div></div>
    <div class="row g-3">@forelse($reviews as $review)<div class="col-lg-6"><article class="admin-panel admin-review"><div class="d-flex justify-content-between gap-3"><div><strong>{{ $review->author_name }}</strong><small class="d-block text-muted">{{ $review->product?->name ?? 'Товар не указан' }} · {{ $review->created_at->format('d.m.Y') }}</small></div><span class="admin-status">{{ $review->is_approved ? 'Опубликован' : 'На проверке' }}</span></div><div class="review-stars my-2">{{ str_repeat('★', $review->rating) }}</div><p>{{ $review->text }}</p>
        @if(auth()->user()->role === 'admin')<div class="admin-actions">@if(!$review->is_approved)<form action="{{ route('admin.reviews.update', $review) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="is_approved" value="1"><button>Одобрить</button></form>@else<form action="{{ route('admin.reviews.update', $review) }}" method="POST">@csrf @method('PATCH')<input type="hidden" name="is_approved" value="0"><button>Снять с публикации</button></form>@endif<form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Удалить отзыв?')">@csrf @method('DELETE')<button class="text-danger">Удалить</button></form></div>@endif
        </article></div>@empty<div class="col-12"><div class="admin-panel text-center text-muted">Отзывов пока нет.</div></div>@endforelse</div>{{ $reviews->links() }}
@endsection
