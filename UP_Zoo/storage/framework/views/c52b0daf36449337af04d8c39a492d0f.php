

<?php $__env->startSection('title', $title.' — UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <section class="page-hero"><div class="container"><span class="eyebrow">ПОДБРАНО С ЗАБОТОЙ</span><h1><?php echo e($title); ?></h1><p><?php echo e($subtitle); ?></p></div></section>
    <section class="section-block"><div class="container">
        <?php if(isset($categories)): ?>
            <form class="filter-panel row g-3 align-items-end mb-4" method="GET" action="<?php echo e(route('catalog')); ?>">
                <div class="col-12 col-md-4"><label class="form-label" for="search">Поиск</label><input class="form-control" id="search" name="q" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Например, корм"></div>
                <div class="col-6 col-md-2"><label class="form-label" for="category">Категория</label><select class="form-select" id="category" name="category"><option value="">Все</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->slug); ?>" <?php if(($filters['category'] ?? '') === $category->slug): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="col-6 col-md-2"><label class="form-label" for="brand">Бренд</label><select class="form-select" id="brand" name="brand"><option value="">Все</option><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($brand->slug); ?>" <?php if(($filters['brand'] ?? '') === $brand->slug): echo 'selected'; endif; ?>><?php echo e($brand->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="col-6 col-md-1"><label class="form-label" for="min_price">От, ₽</label><input class="form-control" id="min_price" type="number" min="0" name="min_price" value="<?php echo e($filters['min_price'] ?? ''); ?>"></div>
                <div class="col-6 col-md-1"><label class="form-label" for="max_price">До, ₽</label><input class="form-control" id="max_price" type="number" min="0" name="max_price" value="<?php echo e($filters['max_price'] ?? ''); ?>"></div>
                <div class="col-12 col-md-2"><button class="btn btn-primary-soft w-100" type="submit">Найти товары</button></div>
            </form>
        <?php endif; ?>
        <div class="row g-4"><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="col-6 col-lg-3"><?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="col-12"><div class="empty-state"><span>🐾</span><h2>Пока ничего не нашлось</h2><p>Попробуйте изменить условия поиска или загляните позже.</p><a href="<?php echo e(route('catalog')); ?>" class="btn btn-outline-purple rounded-pill">Сбросить фильтры</a></div></div><?php endif; ?></div>
        <div class="mt-4"><?php echo e($products->links()); ?></div>
    </div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/pages/products.blade.php ENDPATH**/ ?>