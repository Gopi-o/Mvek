@extends('layouts.app')

@section('title', 'Матчи раунда — ' . $round->name)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">
                {{ $round->tournament->name }} · Раунд #{{ $round->round_number }}
            </div>
            <h1 class="mb-0">{{ $round->name }}</h1>
        </div>
        <a href="{{ route('tournaments.rounds', $round->tournament) }}" class="btn btn-outline-light">Назад</a>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex flex-column gap-3">
                @forelse($round->matches->sortBy('match_number') as $match)
                    @php
                        $p1 = $match->matchParticipants->firstWhere('place', 1)?->participant;
                        $p2 = $match->matchParticipants->firstWhere('place', 2)?->participant;
                    @endphp
                    <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Матч #{{ $match->match_number }} · {{ strtoupper($match->status) }}</div>
                                <div class="fw-semibold">
                                    <span class="{{ $match->winner?->participant_id === $p1?->id ? 'text-accent' : '' }}">{{ $p1?->name ?? '—' }}</span>
                                    <span class="text-muted">vs</span>
                                    <span class="{{ $match->winner?->participant_id === $p2?->id ? 'text-accent' : '' }}">{{ $p2?->name ?? '—' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('matches.show', $match) }}" class="btn btn-primary">Открыть</a>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">В этом раунде пока нет матчей.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

