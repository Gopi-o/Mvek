@extends('layouts.app')

@section('title', 'Редактирование — ' . $tournament->name)

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="text-muted text-uppercase small" style="letter-spacing: 0.12em;">Управление</div>
            <h1 class="mb-0">Редактирование турнира</h1>
        </div>
        <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-light">Назад</a>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('tournaments.update', $tournament) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-8">
                    <label class="form-label">Название</label>
                    <input name="name" class="form-control" value="{{ old('name', $tournament->name) }}" required>
                    @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Статус</label>
                    <select name="status" class="form-select">
                        @foreach(['draft' => 'Подготовка', 'active' => 'Активный', 'completed' => 'Завершён'] as $k => $v)
                            <option value="{{ $k }}" {{ old('status', $tournament->status) === $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('tournaments.show', $tournament) }}" class="btn btn-outline-light">Отмена</a>
                </div>
            </form>

            <hr class="border-theme my-4">
            <form action="{{ route('tournaments.destroy', $tournament) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-light" onclick="return confirm('Удалить турнир?')">Удалить турнир</button>
            </form>
        </div>
    </div>
</div>
@endsection

