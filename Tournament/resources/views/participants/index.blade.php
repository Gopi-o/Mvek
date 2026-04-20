@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-2">Мои участники и команды</h1>
    <p class="text-muted mb-4">Управляй своим игроком и командами. Товарищей можно добавлять по нику без регистрации.</p>

    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <div class="text-muted small">Мой игрок</div>
                    <h4 class="mb-1">{{ $myPlayer->name }}</h4>
                    <div class="text-muted small">Турниров: {{ $myPlayer->tournaments_count }}</div>
                </div>
                <div>
                    <div class="text-muted small">Публичный ID</div>
                    <div class="fw-semibold">{{ auth()->user()->public_id ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body p-4">
            <h5 class="mb-3">Создать команду</h5>
            <form method="POST" action="{{ route('teams.store') }}" class="d-flex gap-2 flex-wrap">
                @csrf
                <input name="name" class="form-control" style="max-width: 420px;" placeholder="Например: Night Owls" required>
                <button class="btn btn-primary" type="submit">Создать</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($myTeams as $team)
            <div class="col-md-6">
                <div class="card card-hover">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-elevated rounded-circle d-flex align-items-center justify-content-center" style="width:64px;height:64px">
                                <span class="text-accent fw-bold" style="font-size:1.5rem">
                                    {{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <h5 class="mb-1">{{ $team->name }}</h5>
                                    <a href="{{ route('teams.show', $team) }}" class="btn btn-sm btn-outline-light">Открыть</a>
                                </div>
                                <span class="badge bg-secondary">Команда</span>
                            </div>
                        </div>
                        <div class="d-flex gap-4 mt-3 pt-3 border-top border-theme">
                            <div>
                                <div class="fw-bold text-accent">{{ $team->tournaments_count }}</div>
                                <small class="text-muted">Турниров</small>
                            </div>
                            <div>
                                <div class="fw-bold text-accent">{{ $team->team_members_count }}</div>
                                <small class="text-muted">Игроков</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted">У тебя пока нет команд. Создай первую выше.</div>
        @endforelse
    </div>
</div>
@endsection