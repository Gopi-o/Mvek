

<?php $__env->startSection('title', ($product->exists ? 'Редактировать товар' : 'Новый товар').' — UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ / ТОВАРЫ</span><h1><?php echo e($product->exists ? 'Редактировать товар' : 'Новый товар'); ?></h1><p>Заполните сведения, которые будут показаны покупателям.</p></div><a class="btn btn-outline-purple" href="<?php echo e(route('admin.products.index')); ?>">← К списку</a></div>
    <form class="admin-panel admin-form" action="<?php echo e($product->exists ? route('admin.products.update', $product) : route('admin.products.store')); ?>" method="POST"><?php echo csrf_field(); ?> <?php if($product->exists): ?><?php echo method_field('PUT'); ?><?php endif; ?>
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Название товара</label><input class="form-control" name="name" value="<?php echo e(old('name', $product->name)); ?>" maxlength="255" required></div>
            <div class="col-md-4"><label class="form-label">URL-идентификатор (slug)</label><input class="form-control" name="slug" value="<?php echo e(old('slug', $product->slug)); ?>" maxlength="255" required></div>
            <div class="col-md-6"><label class="form-label">Категория</label><select class="form-select" name="category_id"><option value="">Не выбрана</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $product->category_id) == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div class="col-md-6"><label class="form-label">Бренд</label><select class="form-select" name="brand_id"><option value="">Не выбран</option><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($brand->id); ?>" <?php if(old('brand_id', $product->brand_id) == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
            <div class="col-12"><label class="form-label">Описание</label><textarea class="form-control" name="description" rows="3" maxlength="5000"><?php echo e(old('description', $product->description)); ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Цена, ₽</label><input class="form-control" type="number" step="0.01" min="0" name="price" value="<?php echo e(old('price', $product->price)); ?>" required></div>
            <div class="col-md-4"><label class="form-label">Цена по акции, ₽</label><input class="form-control" type="number" step="0.01" min="0" name="sale_price" value="<?php echo e(old('sale_price', $product->sale_price)); ?>"></div>
            <div class="col-md-4"><label class="form-label">Количество на складе</label><input class="form-control" type="number" min="0" name="stock" value="<?php echo e(old('stock', $product->stock ?? 0)); ?>" required></div>
            <div class="col-12"><label class="form-label">Ссылка на изображение</label><input class="form-control" type="url" name="image_url" value="<?php echo e(old('image_url', $product->image_url)); ?>" maxlength="2048"></div>
            <div class="col-12 d-flex flex-wrap gap-4"><label class="form-check"><input class="form-check-input" type="checkbox" name="is_new" value="1" <?php if(old('is_new', $product->is_new)): echo 'checked'; endif; ?>> Новинка</label><label class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" <?php if(old('is_featured', $product->is_featured)): echo 'checked'; endif; ?>> Рекомендуемый товар</label></div>
            <div class="col-12"><button class="btn btn-primary-soft"><?php echo e($product->exists ? 'Сохранить товар' : 'Создать товар'); ?></button></div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/admin/products/form.blade.php ENDPATH**/ ?>