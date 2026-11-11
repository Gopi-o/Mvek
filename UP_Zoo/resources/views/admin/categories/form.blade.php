@extends('layouts.admin')

@section('title', ($category->exists ? 'Изменить категорию' : 'Новая категория').' — UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ / КАТЕГОРИИ</span><h1>{{ $category->exists ? 'Изменить категорию' : 'Новая категория' }}</h1></div><a class="btn btn-outline-purple" href="{{ route('admin.categories.index') }}">← К списку</a></div>
    <form class="admin-panel admin-form" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">@csrf @if($category->exists)@method('PUT')@endif<div class="row g-3"><div class="col-md-6"><label class="form-label">Название</label><input class="form-control" name="name" value="{{ old('name', $category->name) }}" required maxlength="255"></div><div class="col-md-6"><label class="form-label">URL-идентификатор</label><input class="form-control" name="slug" value="{{ old('slug', $category->slug) }}" required maxlength="255"></div><div class="col-md-6"><label class="form-label">Иконка</label><input class="form-control" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="20"></div><div class="col-12"><label class="form-label">Описание</label><textarea class="form-control" name="description" rows="3" maxlength="1000">{{ old('description', $category->description) }}</textarea></div><div class="col-12"><button class="btn btn-primary-soft">Сохранить</button></div></div></form>
@endsection
