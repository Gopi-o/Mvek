@extends('layouts.app')

@section('title', 'Раунды — ' . $tournament->name)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">Турнир</div>
            <h1 class="mb-0">Раунды: {{ $tournament->name }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-light">Назад</a>
            <a href="{{ route('tournaments.matches', $tournament) }}" class="btn btn-outline-light">Матчи</a>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">
        @forelse($rounds as $round)
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Раунд #{{ $round->round_number }}</div>
                            <h5 class="mb-0">{{ $round->name }}</h5>
                        </div>
                        <a href="{{ route('rounds.show', $round) }}" class="btn btn-primary">Открыть</a>
                    </div>
                    <div class="mt-3 text-muted small">
                        Матчей: <span class="text-accent fw-bold">{{ $round->matches->count() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted">Раунды ещё не созданы.</div>
        @endforelse
    </div>
</div>
@endsection

