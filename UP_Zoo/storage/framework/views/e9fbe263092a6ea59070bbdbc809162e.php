

<?php $__env->startSection('title', 'Товары — панель UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-heading"><div><span class="eyebrow">КАТАЛОГ</span><h1>Товары</h1><p>Добавление и редактирование товаров, цен и наличия.</p></div><a class="btn btn-primary-soft" href="<?php echo e(route('admin.products.create')); ?>">+ Добавить товар</a></div>
    <section class="admin-panel">
        <form class="admin-search mb-3" method="GET"><input class="form-control" name="q" value="<?php echo e(request('q')); ?>" placeholder="Найти товар по названию"><button class="btn btn-outline-purple">Найти</button></form>
        <div class="table-responsive"><table class="table admin-table"><thead><tr><th>Товар</th><th>Категория</th><th>Бренд</th><th>Цена</th><th>Остаток</th><th>Метки</th><th></th></tr></thead><tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><strong><?php echo e($product->name); ?></strong><small class="d-block text-muted"><?php echo e($product->slug); ?></small></td><td><?php echo e($product->category?->name ?? '—'); ?></td><td><?php echo e($product->brand?->name ?? '—'); ?></td><td><?php echo e(number_format((float) $product->current_price, 0, ',', ' ')); ?> ₽</td><td><?php echo e($product->stock); ?></td><td><?php if($product->sale_price): ?><span class="admin-status">Акция</span><?php endif; ?> <?php if($product->is_new): ?><span class="admin-status">Новинка</span><?php endif; ?> <?php if($product->is_featured): ?><span class="admin-status">Популярный</span><?php endif; ?></td><td><div class="admin-actions"><a href="<?php echo e(route('admin.products.edit', $product)); ?>">Изменить</a><form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" onsubmit="return confirm('Удалить товар?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button>Удалить</button></form></div></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="7" class="text-center text-muted py-4">Товары не найдены</td></tr><?php endif; ?>
        </tbody></table></div><?php echo e($products->links()); ?>

    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/admin/products/index.blade.php ENDPATH**/ ?>