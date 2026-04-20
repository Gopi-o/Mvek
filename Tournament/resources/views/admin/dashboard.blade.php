@extends('layouts.app')

@section('title', 'Админка')

@section('content')
<div class="py-4">
    <h1 class="mb-4">Админка</h1>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="text-muted small">Пользователи</div><div class="h4 mb-0 text-accent">{{ $stats['users'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="text-muted small">Турниры</div><div class="h4 mb-0 text-accent">{{ $stats['tournaments'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="text-muted small">Участники</div><div class="h4 mb-0 text-accent">{{ $stats['participants'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="text-muted small">Активные турниры</div><div class="h4 mb-0 text-accent">{{ $stats['active_tournaments'] }}</div></div></div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Последние пользователи</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach($recentUsers as $u)
                            <div class="p-2 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div class="fw-semibold">{{ $u->name }} <span class="text-muted small">({{ $u->role }})</span></div>
                                <div class="text-muted small">{{ $u->email }} · ID: {{ $u->public_id }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-3">Последние турниры</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach($recentTournaments as $t)
                            <div class="p-2 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
                                <div class="fw-semibold">{{ $t->name }}</div>
                                <div class="text-muted small">{{ $t->status }} · {{ $t->owner?->name }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

