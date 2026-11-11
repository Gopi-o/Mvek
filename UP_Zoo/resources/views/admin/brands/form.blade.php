@extends('layouts.admin')

@section('title', ($brand->exists ? 'Изменить бренд' : 'Новый бренд').' — UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ / БРЕНДЫ</span><h1>{{ $brand->exists ? 'Изменить бренд' : 'Новый бренд' }}</h1></div><a class="btn btn-outline-purple" href="{{ route('admin.brands.index') }}">← К списку</a></div>
    <form class="admin-panel admin-form" action="{{ $brand->exists ? route('admin.brands.update', $brand) : route('admin.brands.store') }}" method="POST">@csrf @if($brand->exists)@method('PUT')@endif<div class="row g-3"><div class="col-md-6"><label class="form-label">Название</label><input class="form-control" name="name" value="{{ old('name', $brand->name) }}" required maxlength="255"></div><div class="col-md-6"><label class="form-label">URL-идентификатор</label><input class="form-control" name="slug" value="{{ old('slug', $brand->slug) }}" required maxlength="255"></div><div class="col-12"><label class="form-label">Описание</label><textarea class="form-control" name="description" rows="3" maxlength="1000">{{ old('description', $brand->description) }}</textarea></div><div class="col-12"><button class="btn btn-primary-soft">Сохранить</button></div></div></form>
@endsection
