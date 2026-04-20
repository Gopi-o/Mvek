@extends('layouts.app')

@section('title', 'Все турниры — Tournament.GGs')

@section('content')
<section class="py-5" style="background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%); border-bottom: 1px solid var(--border);">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-6">
                <span class="d-block mb-2" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ БИБЛИОТЕКА ]</span>
                <h1 style="font-size: 3rem; font-weight: 800; letter-spacing: -0.02em;">ВСЕ ТУРНИРЫ</h1>
                <p class="text-secondary">Просматривайте активные соревнования, фильтруйте по типу и статусу.</p>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-lg">СОЗДАТЬ НОВЫЙ</a>
            </div>
        </div>
    </div>
</section>

<section class="py-4 bg-dark border-bottom border-theme">
    <div class="container">
        <div class="p-4 rounded" style="background: var(--bg-elevated); border: 1px solid var(--border);">
            <form method="GET" action="{{ route('tournaments.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="d-block mb-2" style="color: var(--text-muted); font-size: 0.7rem; letter-spacing: 0.1em;">ПОИСК</label>
                    <input type="text" name="search" class="form-control" placeholder="Название или организатор" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="d-block mb-2" style="color: var(--text-muted); font-size: 0.7rem; letter-spacing: 0.1em;">КАТЕГОРИЯ</label>
                    <select name="category" class="form-select">
                        <option value="">Все</option>
                        <option value="gaming" {{ request('category') == 'gaming' ? 'selected' : '' }}>Игровые</option>
                        <option value="non-gaming" {{ request('category') == 'non-gaming' ? 'selected' : '' }}>Неигровые</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="d-block mb-2" style="color: var(--text-muted); font-size: 0.7rem; letter-spacing: 0.1em;">ФОРМАТ</label>
                    <select name="format" class="form-select">
                        <option value="">Все</option>
                        <option value="individual" {{ request('format') == 'individual' ? 'selected' : '' }}>Индивидуальный</option>
                        <option value="team" {{ request('format') == 'team' ? 'selected' : '' }}>Командный</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="d-block mb-2" style="color: var(--text-muted); font-size: 0.7rem; letter-spacing: 0.1em;">СТАТУС</label>
                    <select name="status" class="form-select">
                        <option value="">Все</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Подготовка</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершённые</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">ПРИМЕНИТЬ</button>
                    @if(request()->hasAny(['search', 'category', 'format', 'status']))
                        <a href="{{ route('tournaments.index') }}" class="btn btn-link w-100 mt-2 text-decoration-none" style="color: var(--text-muted); font-size: 0.8rem;">СБРОСИТЬ</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

@auth
    @if($myTournaments && $myTournaments->count() > 0)
    <section class="py-5" style="background: var(--bg-secondary); border-bottom: 1px solid var(--border);">
        <div class="container">
            <span class="d-block mb-2" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ УПРАВЛЕНИЕ ]</span>
            <h2 class="mb-4">МОИ ТУРНИРЫ</h2>
            
            <div class="d-flex flex-column gap-3">
                @foreach($myTournaments as $tournament)
                <div class="card card-hover" style="border-color: var(--accent-orange);">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-10">
                                <h5 class="card-title">{{ $tournament->name }}</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="text-muted small text-uppercase">{{ $tournament->discipline }}</span>
                                    <span class="text-muted small">|</span>
                                    <span class="text-muted small text-uppercase">
                                        {{ $tournament->is_team_based ? 'КОМАНДНЫЙ' : 'ИНДИВИДУАЛЬНЫЙ' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 col-6 mt-3 mt-md-0">
                                <span class="badge bg-{{ $tournament->status === 'active' ? 'success' : ($tournament->status === 'completed' ? 'secondary' : 'warning') }}">
                                    {{ $tournament->status === 'active' ? 'LIVE' : ($tournament->status === 'completed' ? 'END' : 'DRAFT') }}
                                </span>
                                <div class="text-muted small mt-1">{{ $tournament->created_at->format('d.m.Y') }}</div>
                            </div>
                            <div class="col-md-3 col-6 mt-3 mt-md-0">
                                <div class="d-flex gap-4">
                                    <div>
                                        <div class="h5 mb-0 text-accent">{{ $tournament->participants->count() }}</div>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Участников</small>
                                    </div>
                                    <div>
                                        <div class="h5 mb-0 text-accent">{{ $tournament->rounds->count() }}</div>
                                        <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Раундов</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-12 mt-3 mt-md-0 text-md-end">
                                <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                    <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-sm" style="background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); font-size: 0.75rem; letter-spacing: 0.05em;">СЕТКА</a>
                                    <a href="{{ route('tournaments.edit', $tournament) }}" class="btn btn-sm" style="background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); font-size: 0.75rem; letter-spacing: 0.05em;">ИЗМЕНИТЬ</a>
                                    <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); font-size: 0.75rem; letter-spacing: 0.05em;" onclick="return confirm('Удалить турнир?')">УДАЛИТЬ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endauth

<section class="py-5">
    <div class="container">
        <span class="d-block mb-2" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ АРХИВ ]</span>
        <h2 class="mb-4">ВСЕ СОРЕВНОВАНИЯ</h2>
        
        <div class="d-flex flex-column gap-3">
            @forelse($tournaments as $tournament)
            <div class="card card-hover">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-1 col-md-2 col-3">
                            <span class="badge bg-{{ $tournament->status === 'active' ? 'success' : ($tournament->status === 'completed' ? 'secondary' : 'warning') }} mt-1">
                                {{ $tournament->status === 'active' ? 'LIVE' : ($tournament->status === 'completed' ? 'END' : 'DRAFT') }}
                            </span>
                        </div>
                        <div class="col-lg-4 col-md-5 col-9">
                            <h5 class="card-title">{{ $tournament->name }}</h5>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="text-muted small text-uppercase">{{ $tournament->discipline }}</span>
                                <span class="text-muted small">|</span>
                                <span class="text-muted small text-uppercase">
                                    {{ $tournament->is_team_based ? 'КОМАНДНЫЙ' : 'ИНДИВИДУАЛЬНЫЙ' }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <small class="text-muted">ORG: {{ strtoupper($tournament->owner->name) }} | {{ $tournament->created_at->format('d.m.Y') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-12 mt-3 mt-lg-0">
                            <div class="d-flex gap-4 justify-content-lg-center">
                                <div class="text-center">
                                    <div class="h4 mb-0 text-accent" style="font-weight: 800;">{{ $tournament->participants->count() }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Участников</small>
                                </div>
                                <div class="text-center" style="border-left: 1px solid var(--border); padding-left: 1.5rem;">
                                    <div class="h4 mb-0 text-accent" style="font-weight: 800;">{{ $tournament->rounds->count() }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Раундов</small>
                                </div>
                                <div class="text-center d-none d-md-block" style="border-left: 1px solid var(--border); padding-left: 1.5rem;">
                                    <div class="h4 mb-0 text-accent" style="font-weight: 800;">{{ $tournament->category === 'gaming' ? 'GAME' : 'OTHER' }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em;">Категория</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-12 mt-3 mt-lg-0 text-lg-end">
                            <div class="d-flex gap-2 justify-content-lg-end flex-wrap align-items-center">
                                <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-sm btn-primary">ПОДРОБНЕЕ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="display-1 text-muted mb-3" style="opacity: 0.3;">///</div>
                <p class="text-muted mb-3 text-uppercase" style="letter-spacing: 0.1em;">Нет турниров по заданным параметрам</p>
                <a href="{{ route('tournaments.create') }}" class="btn btn-primary">СОЗДАТЬ ПЕРВЫЙ</a>
            </div>
            @endforelse
        </div>
        
        @if($tournaments->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $tournaments->links() }}
        </div>
        @endif
    </div>
</section>
@endsection