@extends('layouts.app')

@section('title', 'Матчи — ' . $tournament->name)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">Турнир</div>
            <h1 class="mb-0">Матчи: {{ $tournament->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-light">Назад</a>
            <a href="{{ route('tournaments.rounds', $tournament) }}" class="btn btn-outline-light">Раунды</a>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($matches as $match)
            @php
                $p1 = $match->matchParticipants->firstWhere('place', 1)?->participant;
                $p2 = $match->matchParticipants->firstWhere('place', 2)?->participant;
            @endphp
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">{{ $match->round?->name }} · Матч #{{ $match->match_number }}</div>
                            <div class="fw-semibold">
                                <span class="{{ $match->winner?->participant_id === $p1?->id ? 'text-accent' : '' }}">{{ $p1?->name ?? '—' }}</span>
                                <span class="text-muted">vs</span>
                                <span class="{{ $match->winner?->participant_id === $p2?->id ? 'text-accent' : '' }}">{{ $p2?->name ?? '—' }}</span>
                            </div>
                            <div class="text-muted small mt-1">
                                Статус: {{ $match->status }}@if($match->scheduled_at) · Время: {{ $match->scheduled_at->format('d.m.Y H:i') }}@endif
                            </div>
                        </div>
                        <a href="{{ route('matches.show', $match) }}" class="btn btn-primary">Открыть</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted">Матчей пока нет.</div>
        @endforelse
    </div>
</div>
@endsection

