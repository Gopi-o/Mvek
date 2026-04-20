@extends('layouts.app')

@section('title', 'Профиль')

@section('content')
<div class="py-4">
    <h1 class="mb-3">Профиль</h1>

    <div class="card">
        <div class="card-body p-4">
            <div class="mb-2"><span class="text-muted">Имя:</span> {{ $user->name }}</div>
            <div class="mb-2"><span class="text-muted">Email:</span> {{ $user->email }}</div>
            <div class="mb-2"><span class="text-muted">Роль:</span> {{ $user->role }}</div>
            <div class="mb-2"><span class="text-muted">Публичный ID:</span> <span class="text-accent fw-bold">{{ $user->public_id }}</span></div>
            <div class="text-muted small mt-3">
                Используй этот ID, чтобы организатор добавил тебя в турнир как со-организатора.
            </div>
        </div>
    </div>
</div>
@endsection

