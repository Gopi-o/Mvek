@extends('layouts.admin')

@section('title', 'Бренды — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ</span><h1>Бренды</h1><p>Управление брендами, представленными в магазине.</p></div><a class="btn btn-primary-soft" href="{{ route('admin.brands.create') }}">+ Добавить бренд</a></div>
    <section class="admin-panel"><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Бренд</th><th>URL</th><th>Товаров</th><th></th></tr></thead><tbody>@forelse($brands as $brand)<tr><td><strong>{{ $brand->name }}</strong><small class="d-block text-muted">{{ $brand->description }}</small></td><td>{{ $brand->slug }}</td><td>{{ $brand->products_count }}</td><td><div class="admin-actions"><a href="{{ route('admin.brands.edit', $brand) }}">Изменить</a><form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('Удалить бренд?')">@csrf @method('DELETE')<button>Удалить</button></form></div></td></tr>@empty<tr><td colspan="4" class="text-center text-muted">Брендов пока нет</td></tr>@endforelse</tbody></table></div>{{ $brands->links() }}</section>
@endsection
