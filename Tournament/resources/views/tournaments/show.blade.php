@extends('layouts.app')

@section('title', $tournament->name . ' — Tournament.GGs')

@section('content')
<div class="py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">
                {{ $tournament->discipline }} · {{ $tournament->is_team_based ? 'Командный' : 'Индивидуальный' }} · {{ strtoupper($tournament->status) }}
            </div>
            <h1 class="mb-1">{{ $tournament->name }}</h1>
            <div class="text-muted">Организатор: {{ $tournament->owner?->name }}</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('tournaments.rounds', $tournament) }}" class="btn btn-outline-light">Раунды</a>
            <a href="{{ route('tournaments.matches', $tournament) }}" class="btn btn-outline-light">Матчи</a>

            @auth
                @if($canManageTournament)
                    <a href="{{ route('tournaments.edit', $tournament) }}" class="btn btn-outline-light">Редактировать</a>
                    <a href="{{ route('tournaments.generate', $tournament) }}" class="btn btn-primary">Сгенерировать сетку</a>
                    <form action="{{ route('tournaments.start', $tournament) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary" type="submit">Старт</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Участники</h5>

                    <div class="d-flex justify-content-between text-muted small mb-3">
                        <div>Всего: <span class="text-accent fw-bold">{{ $tournament->participants->count() }}</span></div>
                        <div>Раундов: <span class="text-accent fw-bold">{{ $tournament->rounds->count() }}</span></div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @forelse($tournament->participants as $p)
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div class="text-truncate">
                                    <div class="fw-semibold">{{ $p->name }}</div>
                                    <div class="text-muted small">{{ $p->type === 'team' ? 'Команда' : 'Игрок' }}</div>
                                </div>
                                <div class="text-muted small">#{{ $p->pivot?->seed }}</div>
                            </div>
                        @empty
                            <div class="text-muted">Пока никто не зарегистрирован.</div>
                        @endforelse
                    </div>

                    @auth
                        @if($tournament->status === 'draft')
                            <hr class="border-theme my-4">
                            @if($canManageTournament)
                                @php
                                    $allParticipants = \App\Models\Participant::orderBy('name')->get();
                                @endphp
                                <h6 class="mb-2">Регистрация участника (подготовка)</h6>
                                <form method="POST" action="{{ route('participants.register', $tournament) }}" class="d-flex flex-column gap-2">
                                    @csrf
                                    <select name="participant_id" class="form-select">
                                        <option value="">Выбери из списка</option>
                                        @foreach($allParticipants as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->type }})</option>
                                        @endforeach
                                    </select>
                                    <input name="participant_ref" class="form-control" placeholder="Или введи ID/точное имя">
                                    <button class="btn btn-primary" type="submit">Добавить</button>
                                </form>
                                <div class="text-muted small mt-2">Совет: лучше добавлять участника через его персональный ID из профиля.</div>
                            @else
                                <h6 class="mb-2">Заявка на участие</h6>
                                <form method="POST" action="{{ route('participants.register', $tournament) }}" class="d-flex flex-column gap-2">
                                    @csrf
                                    <select name="participant_id" class="form-select">
                                        <option value="">Автовыбор подходящего профиля</option>
                                        @foreach($availableSelfParticipants as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->type }})</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="submit">Зарегистрироваться</button>
                                </form>
                                @if($availableSelfParticipants->isEmpty())
                                    <div class="text-muted small mt-2">
                                        Нет подходящего участника. Создай его в разделе «Участники».
                                    </div>
                                @endif
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Сетка (по раундам)</h5>
                        <div class="text-muted small">
                            <a class="text-decoration-none" style="color: var(--text-muted)" href="{{ route('tournaments.rounds', $tournament) }}">Открыть подробно</a>
                        </div>
                    </div>

                    @if($tournament->rounds->count() === 0)
                        <div class="text-muted">Сетка ещё не создана. Нажми «Сгенерировать сетку».</div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($tournament->rounds->sortBy('round_number') as $round)
                                <div class="p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">{{ $round->name }}</div>
                                        <a href="{{ route('rounds.show', $round) }}" class="btn btn-sm btn-outline-light">Матчи</a>
                                    </div>

                                    <div class="d-flex flex-column gap-2">
                                        @forelse($round->matches->sortBy('match_number') as $match)
                                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--bg-card); border: 1px solid var(--border);">
                                                <div class="text-truncate">
                                                    <div class="text-muted small">Матч #{{ $match->match_number }} · {{ strtoupper($match->status) }}</div>
                                                    <div class="small">
                                                        @php
                                                            $p1 = $match->matchParticipants->firstWhere('place', 1)?->participant;
                                                            $p2 = $match->matchParticipants->firstWhere('place', 2)?->participant;
                                                        @endphp
                                                        <span class="{{ $match->winner?->participant_id === $p1?->id ? 'text-accent fw-bold' : '' }}">
                                                            {{ $p1?->name ?? '—' }}
                                                        </span>
                                                        <span class="text-muted">vs</span>
                                                        <span class="{{ $match->winner?->participant_id === $p2?->id ? 'text-accent fw-bold' : '' }}">
                                                            {{ $p2?->name ?? '—' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <a href="{{ route('matches.show', $match) }}" class="btn btn-sm btn-primary">Открыть</a>
                                            </div>
                                        @empty
                                            <div class="text-muted small">Матчи ещё не созданы.</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @auth
        @if(auth()->id() === $tournament->owner_id)
            <div class="card mt-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Со-организаторы</h5>
                    <form method="POST" action="{{ route('tournaments.managers.store', $tournament) }}" class="d-flex gap-2 flex-wrap">
                        @csrf
                        <input name="public_id" class="form-control" style="max-width: 260px;" placeholder="Публичный ID пользователя">
                        <button class="btn btn-primary" type="submit">Добавить</button>
                    </form>
                    <div class="text-muted small mt-2">
                        Со-организаторы могут управлять турниром и матчами, но не могут удалить турнир.
                    </div>

                    <div class="d-flex flex-column gap-2 mt-3">
                        @forelse($tournament->managers as $manager)
                            <div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div>
                                    <div class="fw-semibold">{{ $manager->name }}</div>
                                    <div class="text-muted small">ID: {{ $manager->public_id }}</div>
                                </div>
                                <form method="POST" action="{{ route('tournaments.managers.destroy', [$tournament, $manager]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-light" type="submit">Убрать</button>
                                </form>
                            </div>
                        @empty
                            <div class="text-muted small">Дополнительных организаторов пока нет.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <div class="card mt-4">
        <div class="card-body p-4">
            @if($tournament->rounds->count() === 0)
                <div class="text-muted">Сетка пока не сформирована.</div>
            @else
                <div class="bracket-board">
                    @foreach($tournament->rounds->sortBy('round_number') as $round)
                        <div class="bracket-round">
                            <div class="bracket-round-title">{{ $round->name }}</div>
                            <div class="d-flex flex-column gap-3">
                                @foreach($round->matches->sortBy('match_number') as $match)
                                    @php
                                        $p1 = $match->matchParticipants->firstWhere('place', 1)?->participant;
                                        $p2 = $match->matchParticipants->firstWhere('place', 2)?->participant;
                                    @endphp
                                    <div class="bracket-match">
                                        <div class="bracket-line {{ $match->winner?->participant_id === $p1?->id ? 'winner' : '' }}">{{ $p1?->name ?? '—' }}</div>
                                        <div class="bracket-line {{ $match->winner?->participant_id === $p2?->id ? 'winner' : '' }}">{{ $p2?->name ?? '—' }}</div>
                                        <div class="bracket-meta">
                                            {{ $match->scheduled_at ? $match->scheduled_at->format('d.m.Y H:i') : 'Время не назначено' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

