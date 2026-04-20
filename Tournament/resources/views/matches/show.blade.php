@extends('layouts.app')

@section('title', 'Матч #' . $match->match_number)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">
                {{ $match->round->tournament->name }} · {{ $match->round->name }}
            </div>
            <h1 class="mb-0">Матч #{{ $match->match_number }}</h1>
        </div>
        <a href="{{ route('tournaments.matches', $match->round->tournament) }}" class="btn btn-outline-light">Назад</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Участники</h5>

                    @php
                        $slots = $match->matchParticipants->sortBy('place')->values();
                    @endphp

                    <div class="d-flex flex-column gap-2">
                        @foreach($slots as $mp)
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div>
                                    <div class="text-muted small">Место: {{ $mp->place ?? '—' }}</div>
                                    <div class="fw-semibold {{ $mp->is_winner ? 'text-accent' : '' }}">
                                        {{ $mp->participant?->name ?? '—' }}
                                        @if($mp->is_winner) <span class="text-muted small">(WIN)</span> @endif
                                    </div>
                                </div>
                                <div class="text-muted small">{{ $mp->score ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 text-muted small">
                        Статус: <span class="text-accent fw-bold">{{ $match->status }}</span>
                        @if($match->scheduled_at)
                            · Назначено: <span class="text-accent fw-bold">{{ $match->scheduled_at->format('d.m.Y H:i') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Управление</h5>

                    @auth
                        @if($match->round->tournament->canBeManagedBy(auth()->user()))
                            <form method="POST" action="{{ route('matches.result', $match) }}" class="mb-4">
                                @csrf
                                <label class="form-label">Победитель</label>
                                <select name="winner_id" class="form-select">
                                    @foreach($match->matchParticipants as $mp)
                                        <option value="{{ $mp->participant_id }}" {{ $mp->is_winner ? 'selected' : '' }}>
                                            {{ $mp->participant?->name ?? ('ID ' . $mp->participant_id) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="form-label mt-3">Счёт</label>
                                <input name="score" class="form-control" placeholder="Напр. 2:1">
                                <button class="btn btn-primary w-100 mt-3" type="submit">Сохранить результат</button>
                            </form>

                            <form method="POST" action="{{ route('matches.schedule', $match) }}">
                                @csrf
                                <label class="form-label">Время матча</label>
                                <input type="datetime-local" name="scheduled_at" class="form-control">
                                <button class="btn btn-outline-light w-100 mt-3" type="submit">Назначить</button>
                            </form>
                        @else
                            <div class="text-muted">Управление доступно только организатору.</div>
                        @endif
                    @else
                        <div class="text-muted">Войди, чтобы управлять матчем.</div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

