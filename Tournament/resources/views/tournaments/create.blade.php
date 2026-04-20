@extends('layouts.app')

@section('title', 'Создать турнир — Tournament.GGs')

@section('content')
<section class="py-5" style="background: linear-gradient(180deg, var(--bg-secondary) 0%, var(--bg-primary) 100%); border-bottom: 1px solid var(--border);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <span class="d-block mb-2" style="color: var(--accent-orange); font-size: 0.8rem; letter-spacing: 0.15em;">[ СОЗДАНИЕ ]</span>
                <h1 style="font-size: 3rem; font-weight: 800; letter-spacing: -0.02em;">НОВЫЙ ТУРНИР</h1>
                <p class="text-secondary">Выберите формат сетки, укажите дисциплину и параметры участников.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card" style="border: 1px solid var(--border);">
                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('tournaments.store') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label">НАЗВАНИЕ ТУРНИРА</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Например: Весенний Кубок 2024"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="discipline" class="form-label">ДИСЦИПЛИНА</label>
                                <input type="text" 
                                       name="discipline" 
                                       id="discipline" 
                                       class="form-control @error('discipline') is-invalid @enderror" 
                                       placeholder="Например: Valorant, CS2, Dota 2, Шахматы"
                                       value="{{ old('discipline') }}"
                                       required>
                                <small class="text-muted" style="font-size: 0.75rem;">Отображается в списке турниров</small>
                                @error('discipline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="type_id" class="form-label">ФОРМАТ СЕТКИ</label>
                                <select name="type_id" 
                                        id="type_id" 
                                        class="form-select @error('type_id') is-invalid @enderror"
                                        required>
                                    <option value="">Выберите формат</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" style="font-size: 0.75rem;">Формат можно изменить позже в настройках турнира.</small>
                                @error('type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="max_teams" class="form-label">МАКСИМУМ УЧАСТНИКОВ</label>
                                    <input type="number" 
                                           name="max_teams" 
                                           id="max_teams" 
                                           class="form-control @error('max_teams') is-invalid @enderror" 
                                           placeholder="16"
                                           value="{{ old('max_teams') }}"
                                           min="2">
                                    @error('max_teams')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" style="font-size: 0.75rem;">Оставьте пустым — без ограничений</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="players_per_team" class="form-label">ИГРОКОВ В КОМАНДЕ</label>
                                    <input type="number" 
                                           name="players_per_team" 
                                           id="players_per_team" 
                                           class="form-control @error('players_per_team') is-invalid @enderror" 
                                           placeholder="1"
                                           value="{{ old('players_per_team', 1) }}"
                                           min="1"
                                           required>
                                    @error('players_per_team')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted" style="font-size: 0.75rem;">1 = индивидуальный, 2+ = командный</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="category" class="form-label">КАТЕГОРИЯ</label>
                                <select name="category" 
                                        id="category" 
                                        class="form-select @error('category') is-invalid @enderror">
                                    <option value="gaming" {{ old('category') == 'gaming' ? 'selected' : '' }}>Игровые</option>
                                    <option value="non-gaming" {{ old('category') == 'non-gaming' ? 'selected' : '' }}>Неигровые</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-3 pt-3 border-top" style="border-color: var(--border) !important;">
                                <button type="submit" class="btn btn-primary btn-lg">СОЗДАТЬ ТУРНИР</button>
                                <a href="{{ route('tournaments.index') }}" class="btn btn-outline-light btn-lg">ОТМЕНА</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection