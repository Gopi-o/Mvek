@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🛠 Админка</h2>
        <nav>
            <div class="nav nav-tabs" id="adminTabs" role="tablist">
                <button class="nav-link @if($tab=='products') active @endif" 
                        onclick="switchTab('products')">
                    🛒 Товары
                </button>
                <button class="nav-link @if($tab=='categories') active @endif" 
                        onclick="switchTab('categories')">
                    📁 Категории
                </button>
                <button class="nav-link @if($tab=='users') active @endif" 
                        onclick="switchTab('users')">
                    👥 Пользователи
                </button>
            </div>
        </nav>
    </div>

    <!-- Таблица -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        @if($tab == 'products')
                            <tr>
                                <th>ID</th><th>Изображение</th><th>Название</th><th>Цена</th><th>Создан</th>
                            </tr>
                        @elseif($tab == 'categories')
                            <tr>
                                <th>ID</th><th>Название</th><th>Slug</th><th>Создан</th>
                            </tr>
                        @else
                            <tr>
                                <th>ID</th><th>Имя</th><th>Email</th><th>Админ</th><th>Создан</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody id="tableBody">
                        @forelse($items as $item)
                            <tr>
                                @if($tab == 'products')
                                    <td><strong>#{{ $item->id }}</strong></td>
                                    <td>
                                        @if($item->image)
                                            <img src="/storage/img/{{ $item->image }}" width="50" class="rounded">
                                        @else <i class="fas fa-image text-muted"></i> @endif
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td><span class="badge bg-success">{{ number_format($item->price) }} ₽</span></td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                @elseif($tab == 'categories')
                                    <td><strong>#{{ $item->id }}</strong></td>
                                    <td>{{ $item->name }}</td>
                                    <td><code>{{ $item->slug }}</code></td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                @else
                                    <td><strong>#{{ $item->id }}</strong></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_admin ? 'bg-danger' : 'bg-secondary' }}">
                                            {{ $item->is_admin ? 'Да' : 'Нет' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d.m.Y') }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $tab=='products' ? 5 : 4 }}" class="text-center text-muted py-4">Нет данных</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5>📝 Выполнить команду <span id="currentTabName" class="badge bg-light text-dark ms-2"></span></h5>
            <div id="commandExamples" class="mt-2">
            </div>
        </div>
        <div class="card-body">
            <form id="commandForm">
                @csrf
                <input type="hidden" name="tab" id="currentTab" value="{{ $tab }}">
                <div class="input-group">
                    <textarea name="command" id="commandInput" class="form-control" rows="3" 
                              placeholder="Введите команду..." required></textarea>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-play"></i> Выполнить
                    </button>
                </div>
            </form>
            <div id="results" class="mt-3"></div>
        </div>
    </div>
</div>

<script>
let currentTab = '{{ $tab }}';

const tabExamples = {
    products: 'edit(1, name="Oculus Quest 3", price=39999) | delete(5) | add(name="Новые VR очки", price=25000)',
    categories: 'edit(1, name="VR Gaming") | delete(2) | add(name="AR очки")',
    users: 'edit(1, name="New Admin", is_admin=true) | delete(3)'
};

function switchTab(tab) {
    currentTab = tab;
    document.getElementById('currentTab').value = tab;
    document.getElementById('currentTabName').textContent = 
        tab.charAt(0).toUpperCase() + tab.slice(1);
    
    document.getElementById('commandExamples').innerHTML = 
        `<small class="text-muted">Примеры: <code>${tabExamples[tab]}</code></small>`;
    
    window.location.href = `/admin/dashboard/${tab}`;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('currentTabName').textContent = 
        currentTab.charAt(0).toUpperCase() + currentTab.slice(1);
    
    document.getElementById('commandExamples').innerHTML = 
        `<small class="text-muted">Примеры: <code>${tabExamples[currentTab]}</code></small>`;
});

document.getElementById('commandForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = this.querySelector('button');
    const results = document.getElementById('results');
    
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Выполняется...';
    results.innerHTML = '';
    
    try {
        const response = await fetch('{{ route("admin.command") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        
        const data = await response.json();
        
        if (data.success) {
            results.innerHTML = `<div class="alert alert-success">${data.success}</div>`;
            setTimeout(() => window.location.reload(), 1500);
        } else {
            results.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
        }
    } catch (error) {
        results.innerHTML = '<div class="alert alert-danger">Ошибка сервера</div>';
    } finally {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-play"></i> Выполнить';
    }
});
</script>
@endsection
