@extends('layouts.admin')

@section('title', ($product->exists ? 'Редактировать товар' : 'Новый товар').' — UP_ZOO')

@section('content')
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ / ТОВАРЫ</span><h1>{{ $product->exists ? 'Редактировать товар' : 'Новый товар' }}</h1><p>Заполните сведения, которые будут показаны покупателям.</p></div><a class="btn btn-outline-purple" href="{{ route('admin.products.index') }}">← К списку</a></div>
    <form class="admin-panel admin-form" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST">@csrf @if($product->exists)@method('PUT')@endif
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Название товара</label><input class="form-control" name="name" value="{{ old('name', $product->name) }}" maxlength="255" required></div>
            <div class="col-md-4"><label class="form-label">URL-идентификатор (slug)</label><input class="form-control" name="slug" value="{{ old('slug', $product->slug) }}" maxlength="255" required></div>
            <div class="col-md-6"><label class="form-label">Категория</label><select class="form-select" name="category_id"><option value="">Не выбрана</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-md-6"><label class="form-label">Бренд</label><select class="form-select" name="brand_id"><option value="">Не выбран</option>@foreach($brands as $brand)<option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>@endforeach</select></div>
            <div class="col-12"><label class="form-label">Описание</label><textarea class="form-control" name="description" rows="3" maxlength="5000">{{ old('description', $product->description) }}</textarea></div>
            <div class="col-md-4"><label class="form-label">Цена, ₽</label><input class="form-control" type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" required></div>
            <div class="col-md-4"><label class="form-label">Цена по акции, ₽</label><input class="form-control" type="number" step="0.01" min="0" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"></div>
            <div class="col-md-4"><label class="form-label">Количество на складе</label><input class="form-control" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required></div>
            <div class="col-12"><label class="form-label">Ссылка на изображение</label><input class="form-control" type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" maxlength="2048"></div>
            <div class="col-12 d-flex flex-wrap gap-4"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_new" value="1" @checked(old('is_new', $product->is_new))> Новинка</label><label class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Рекомендуемый товар</label></div>
            <div class="col-12"><button class="btn btn-primary-soft">{{ $product->exists ? 'Сохранить товар' : 'Создать товар' }}</button></div>
        </div>
    </form>
@endsection
