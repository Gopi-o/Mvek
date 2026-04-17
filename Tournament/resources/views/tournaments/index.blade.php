@extends('layouts.app')

@section('title', 'Турниры')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Турниры</h1>
    <a href="{{ route('tournaments.create') }}" class="btn btn-primary">Создать турнир</a>
</div>

<div class="row">
    @forelse($tournaments as $tournament)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card tournament-card">
                <div class="card-body">
                    <h5 class="card-title">{{ $tournament->name }}</h5>
                    <p class="card-text">
                        <span class="badge bg-{{ $tournament->status === 'active' ? 'success' : ($tournament->status === 'completed' ? 'secondary' : 'warning') }}">
                            {{ $tournament->status === 'active' ? 'Активен' : ($tournament->status === 'completed' ? 'Завершён' : 'Черновик') }}
                        </span>
                        <span class="badge bg-info">{{ $tournament->type->name ?? 'Не указан' }}</span>
                    </p>
                    <p class="card-text text-muted">
                        Участников: {{ $tournament->participants->count() }}
                    </p>
                    <p class="card-text small text-muted">
                        Создал: {{ $tournament->owner->name }}
                    </p>
                    <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-primary btn-sm">
                        Подробнее
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">Пока нет созданных турниров</div>
        </div>
    @endforelse
</div>
@endsection