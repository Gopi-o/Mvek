<?php $__env->startSection('title', 'Сравнение товаров'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Сравнение товаров</h1>
        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>В каталог
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($products->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Изображение</th>
                        <th>Модель</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Наличие</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <img src="<?php echo e($product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80x80?text=VR'); ?>" 
                                 class="rounded" width="80" height="80" style="object-fit: cover;" alt="<?php echo e($product->name); ?>">
                        </td>
                        <td>
                            <strong><?php echo e($product->name); ?></strong><br>
                            <small class="text-muted"><?php echo e(Str::limit($product->description, 60)); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?php echo e($product->category->name); ?></span>
                        </td>
                        <td>
                            <div class="h5 text-danger fw-bold mb-0"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽</div>
                        </td>
                        <td>
                            <?php if($product->stock > 0): ?>
                                <span class="badge bg-success"><?php echo e($product->stock); ?> шт.</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Нет в наличии</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('compare.remove')); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Удалить из сравнения?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-balance-scale fa-4x text-muted mb-4"></i>
            <h4>Товары для сравнения</h4>
            <p class="text-muted mb-4">Добавьте товары из каталога для сравнения характеристик.</p>
            <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Перейти в каталог
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/compare/index.blade.php ENDPATH**/ ?>