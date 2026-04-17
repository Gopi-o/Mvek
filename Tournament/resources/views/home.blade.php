@extends('layouts.app')

@section('title', 'Tournament.GGs — Автоматизация турниров')

@section('content')
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-7">
                <div class="hero-content">
                    <span class="hero-badge">ВЕРСИЯ 1.0</span>
                    <h1 class="hero-title">
                        TOURNAMENT<br>
                        <span class="text-accent">AUTOMATION</span>
                    </h1>
                    <p class="hero-description">
                        Платформа для автоматизации организации турниров. 
                        Забудьте о громоздких таблицах, ручных расчётах и несогласованных данных. 
                        Создавайте соревнования любого масштаба за минуты — 
                        без технических навыков и сложных настроек.
                    </p>
                    <div class="hero-features">
                        <div class="feature-item">
                            <span class="feature-icon">◆</span>
                            <span>Single Elimination</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">◆</span>
                            <span>Автоматическая сетка</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">◆</span>
                            <span>Учёт результатов</span>
                        </div>
                    </div>
                    <div class="hero-actions">
                        <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-lg">
                            СОЗДАТЬ ТУРНИР
                        </a>
                        <a href="{{ route('tournaments.index') }}" class="btn btn-outline-light btn-lg">
                            СМОТРЕТЬ ВСЕ
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-visual">
                    <div class="tournament-preview">
                        <div class="bracket-lines">
                            <div class="bracket-match">VS</div>
                            <div class="bracket-connector"></div>
                            <div class="bracket-final">🏆</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>SCROLL</span>
        <div class="scroll-line"></div>
    </div>
</section>

<section class="stats-bar">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ $latestTournaments->count() }}</span>
                    <span class="stat-label">АКТИВНЫХ ТУРНИРОВ</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">1V1</span>
                    <span class="stat-label">ФОРМАТ БОЕВ</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">AUTO</span>
                    <span class="stat-label">ГЕНЕРАЦИЯ СЕТКИ</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="tournaments-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">[ ПОСЛЕДНИЕ ]</span>
            <h2 class="section-title">АКТИВНЫЕ СОРЕВНОВАНИЯ</h2>
        </div>
        
        <div class="row">
            @forelse($latestTournaments as $tournament)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="tournament-card">
                        <div class="card-glow"></div>
                        <div class="card-body">
                            <div class="card-header-row">
                                <span class="tournament-id">#{{ $tournament->id }}</span>
                                <span class="status-badge status-{{ $tournament->status }}">
                                    {{ $tournament->status === 'active' ? '● LIVE' : ($tournament->status === 'completed' ? '■ END' : '◈ DRAFT') }}
                                </span>
                            </div>
                            <h3 class="tournament-name">{{ $tournament->name }}</h3>
                            <p class="tournament-type">{{ $tournament->type->name ?? 'STANDARD' }}</p>
                            <div class="tournament-stats">
                                <div class="stat">
                                    <span class="stat-value">{{ $tournament->participants->count() }}</span>
                                    <span class="stat-name">УЧАСТНИКОВ</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-value">{{ $tournament->rounds->count() }}</span>
                                    <span class="stat-name">РАУНДОВ</span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span class="owner">BY {{ strtoupper($tournament->owner->name) }}</span>
                                <a href="{{ route('tournaments.show', $tournament) }}" class="btn-card">
                                    ПОДРОБНЕЕ →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">◉</div>
                        <p>НЕТ АКТИВНЫХ ТУРНИРОВ</p>
                        <a href="{{ route('tournaments.create') }}" class="btn btn-primary">
                            СОЗДАТЬ ПЕРВЫЙ
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('tournaments.index') }}" class="btn btn-outline-primary btn-all">
                ВСЕ ТУРНИРЫ →
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-badge">[ ВОЗМОЖНОСТИ ]</span>
            <h2 class="section-title">ПОЧЕМУ Tournament.GGs</h2>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-number">01</div>
                    <h3>АВТОМАТИЧЕСКАЯ СЕТКА</h3>
                    <p>Система мгновенно генерирует турнирную сетку Single Elimination, распределяя участников по раундам и создавая пары автоматически.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-number">02</div>
                    <h3>УЧЁТ РЕЗУЛЬТАТОВ</h3>
                    <p>Фиксируйте победителей каждого матча в один клик. Система автоматически продвигает их в следующий раунд до финала.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-number">03</div>
                    <h3>ПРОСТОТА ИСПОЛЬЗОВАНИЯ</h3>
                    <p>Интуитивный интерфейс без лишних настроек. Создайте турнир за 2 минуты — от идеи до готовой сетки.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container text-center">
        <h2 class="cta-title">ГОТОВЫ К СОРЕВНОВАНИЮ?</h2>
        <p class="cta-text">Присоединяйтесь к тысячам организаторов, которые уже автоматизировали свои турниры</p>
        <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-xl">
            НАЧАТЬ СЕЙЧАС
        </a>
    </div>
</section>
@endsection