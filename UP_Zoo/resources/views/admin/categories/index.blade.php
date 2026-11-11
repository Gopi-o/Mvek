@extends('layouts.admin')

@section('title', 'Категории — панель UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ</span><h1>Категории</h1><p>Категории помогают покупателям находить подходящие товары.</p></div><a class="btn btn-primary-soft" href="{{ route('admin.categories.create') }}">+ Добавить категорию</a></div>
    <section class="admin-panel"><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Категория</th><th>URL</th><th>Товаров</th><th></th></tr></thead><tbody>@forelse($categories as $category)<tr><td>{{ $category->icon }} {{ $category->name }}</td><td>{{ $category->slug }}</td><td>{{ $category->products_count }}</td><td><div class="admin-actions"><a href="{{ route('admin.categories.edit', $category) }}">Изменить</a><form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию?')">@csrf @method('DELETE')<button>Удалить</button></form></div></td></tr>@empty<tr><td colspan="4" class="text-center text-muted">Категорий пока нет</td></tr>@endforelse</tbody></table></div>{{ $categories->links() }}</section>
@endsection
