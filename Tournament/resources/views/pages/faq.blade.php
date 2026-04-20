@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="py-4">
    <h1 class="mb-4">FAQ</h1>

    <div class="card mb-3">
        <div class="card-body p-4">
            <h5>Как попасть в турнир?</h5>
            <p class="mb-0 text-muted">Участник регистрируется через организатора турнира. Организатор добавляет участника в список регистрации на странице турнира.</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body p-4">
            <h5>Можно ли добавить участника только по ID без проверки?</h5>
            <p class="mb-0 text-muted">Нет. Рекомендуется проверять имя участника и его данные перед добавлением. ID используется как вспомогательный идентификатор, а не как единственный критерий.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h5>Что может со-организатор?</h5>
            <p class="mb-0 text-muted">Со-организатор может вести матчи, назначать время и управлять ходом турнира, но не может удалить турнир.</p>
        </div>
    </div>
</div>
@endsection

