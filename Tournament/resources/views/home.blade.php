@extends('layouts.app')

@section('title', 'Tournament.GGs')

@section('content')
<!-- Hero -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-7">
                <span class="hero-badge mb-3">ВЕРСИЯ 1.0</span>
                <h1 class="hero-title mb-4">
                    TOURNAMENT<br>
                    <span class="text-accent">AUTOMATION</span>
                </h1>
                <p class="text-secondary mb-4 fs-5">
                    Платформа для автоматизации организации турниров. 
                    Забудьте о громоздких таблицах и ручных расчётах.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-lg">СОЗДАТЬ ТУРНИР</a>
                    <a href="{{ route('tournaments.index') }}" class="btn btn-outline-light btn-lg">СМОТРЕТЬ ВСЕ</a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-dark py-5 border-top border-bottom border-theme">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-number">{{ $latestTournaments->count() }}</div>
                <div class="stat-label">АКТИВНЫХ ТУРНИРОВ</div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="stat-number">1V1</div>
                <div class="stat-label">ФОРМАТ БОЕВ</div>
            </div>
            <div class="col-md-4">
                <div class="stat-number">AUTO</div>
                <div class="stat-label">ГЕНЕРАЦИЯ СЕТКИ</div>
            </div>
        </div>
    </div>
</section>

<!-- Tournaments -->
<section class="py-5">
    <div class="container">
        <span class="section-badge d-block mb-2">[ ПОСЛЕДНИЕ ]</span>
        <h2 class="mb-4">АКТИВНЫЕ СОРЕВНОВАНИЯ</h2>
        
        <div class="row">
            @forelse($latestTournaments as $tournament)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted font-monospace"></small>
                                <span class="badge bg-{{ $tournament->status === 'active' ? 'success' : ($tournament->status === 'completed' ? 'secondary' : 'warning') }}">
                                    {{ $tournament->status === 'active' ? 'LIVE' : ($tournament->status === 'completed' ? 'END' : 'DRAFT') }}
                                </span>
                            </div>
                            
                            
                            <h5 class="card-title">{{ $tournament->name }}</h5>
                            <p class="text-muted small text-uppercase">{{ $tournament->type->name ?? 'STANDARD' }}</p>
                            
                            <div class="d-flex gap-4 my-3 py-3 border-top border-bottom border-theme">
                                <div>
                                    <div class="h4 mb-0 text-accent">{{ $tournament->participants->count() }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Участников</small>
                                </div>
                                <div>
                                    <div class="h4 mb-0 text-accent">{{ $tournament->rounds->count() }}</div>
                                    <small class="text-muted text-uppercase" style="font-size: 0.7rem;">Раундов</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">BY {{ strtoupper($tournament->owner->name) }}</small>
                                <a href="{{ route('tournaments.show', $tournament) }}" class="text-decoration-none fw-semibold" style="color: var(--accent-orange);">
                                    Подробнее →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="display-1 text-muted mb-3">◉</div>
                    <p class="text-muted mb-3">НЕТ АКТИВНЫХ ТУРНИРОВ</p>
                    <a href="{{ route('tournaments.create') }}" class="btn btn-primary">СОЗДАТЬ ПЕРВЫЙ</a>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('tournaments.index') }}" class="btn btn-outline-primary">ВСЕ ТУРНИРЫ →</a>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-5 bg-secondary">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge d-block mb-2">[ ВОЗМОЖНОСТИ ]</span>
            <h2>ПОЧЕМУ Tournament.GGs</h2>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 p-4">
                    <div class="display-3 fw-bold text-accent opacity-25">01</div>
                    <h5 class="mt-3">АВТОМАТИЧЕСКАЯ СЕТКА</h5>
                    <p class="text-secondary">Мгновенная генерация турнирной сетки Single Elimination с автоматическим распределением участников.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 p-4">
                    <div class="display-3 fw-bold text-accent opacity-25">02</div>
                    <h5 class="mt-3">УЧЁТ РЕЗУЛЬТАТОВ</h5>
                    <p class="text-secondary">Фиксация победителей в один клик с автоматическим продвижением в следующий раунд.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 p-4">
                    <div class="display-3 fw-bold text-accent opacity-25">03</div>
                    <h5 class="mt-3">ПРОСТОТА ИСПОЛЬЗОВАНИЯ</h5>
                    <p class="text-secondary">Создайте турнир за 2 минуты без технических навыков и сложных настроек.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section py-5">
    <div class="container text-center">
        <h2 class="cta-title mb-3">ГОТОВЫ К СОРЕВНОВАНИЮ?</h2>
        <p class="mb-4 opacity-75">Присоединяйтесь к организаторам, которые уже автоматизировали свои турниры</p>
        <a href="{{ route('tournaments.create') }}" class="btn btn-light btn-lg text-dark fw-bold">НАЧАТЬ СЕЙЧАС</a>
    </div>
</section>
@endsection