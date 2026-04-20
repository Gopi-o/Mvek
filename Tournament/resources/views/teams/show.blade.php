@extends('layouts.app')

@section('title', 'Команда — ' . $team->name)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">Команда</div>
            <h1 class="mb-0">{{ $team->name }}</h1>
        </div>
        <a href="{{ route('participants.index') }}" class="btn btn-outline-light">Назад</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Состав</h5>

                    <div class="d-flex flex-column gap-2">
                        @forelse($team->teamMembers as $member)
                            <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div>
                                    <div class="fw-semibold">{{ $member->display_name }}</div>
                                    <div class="text-muted small">
                                        {{ $member->user ? 'Игрок с аккаунтом' : 'Игрок без аккаунта' }}
                                        @if($member->joined_at)
                                            · {{ $member->joined_at->format('d.m.Y H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('teams.removeMember', $team) }}">
                                    @csrf
                                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-light">Удалить</button>
                                </form>
                            </div>
                        @empty
                            <div class="text-muted">Пока нет игроков.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Добавить игрока</h5>
                    <form method="POST" action="{{ route('teams.addMember', $team) }}">
                        @csrf
                        <label class="form-label">Ник товарища (без регистрации)</label>
                        <input name="guest_name" class="form-control mb-3" placeholder="Например: Viper_21">

                        <div class="text-muted small text-center my-2">или</div>

                        <label class="form-label">Публичный ID пользователя (если есть аккаунт)</label>
                        <input name="public_id" class="form-control" placeholder="Например: A1B2C3D4E5">

                        <button class="btn btn-primary w-100 mt-3" type="submit">Добавить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

