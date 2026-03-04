<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('title', $product->name . ' — подробное описание'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm bg-white">
                    <img src="<?php echo e($product->image ? asset('storage/img/' . $product->image) : asset('storage/img/vr-ochki-def.jpg')); ?>" 
                         class="w-100 h-100 object-fit-cover" alt="<?php echo e($product->name); ?>">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="badge bg-primary"><?php echo e($product->category->name); ?></span>
                    <?php if($product->stock > 0): ?>
                        <span class="text-success fw-semibold"><?php echo e($product->stock); ?> шт. в наличии</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Нет в наличии</span>
                    <?php endif; ?>
                </div>
                <h1 class="h3 fw-bold mb-3"><?php echo e($product->name); ?></h1>
                <p class="text-muted fs-6"><?php echo e($product->description); ?></p>

                <div class="d-flex align-items-center gap-3 my-4">
                    <div class="display-6 text-danger fw-bold mb-0">
                        <?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <?php if($product->stock > 0): ?>
                        <button class="btn btn-success" onclick="addToCart(<?php echo e($product->id); ?>)">
                            <i class="fas fa-cart-plus me-2"></i>В корзину
                        </button>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('compare.add')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-balance-scale me-2"></i>Добавить в сравнение
                        </button>
                    </form>

                    <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Назад к каталогу
                    </a>
                </div>

                <div class="mt-4">
                    <h5 class="fw-semibold">Почему стоит выбрать</h5>
                    <ul class="list-unstyled text-muted mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Гарантия 12 месяцев и быстрая доставка</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Поддержка популярных платформ и игр</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Подробные инструкции и помощь в настройке</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if($related->count()): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0">Похожие товары</h2>
            <a href="<?php echo e(route('catalog.index', ['category' => $product->category->slug])); ?>" class="text-decoration-none">Смотреть все</a>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
                <div class="card h-100 shadow-sm border-0 hover-shadow">
                    <div class="card-img-top position-relative overflow-hidden" style="height: 180px;">
                        <img src="<?php echo e($item->image ? asset('storage/img/' . $item->image) : asset('storage/img/vr-ochki-def.jpg')); ?>" 
                             class="w-100 h-100 object-fit-cover" alt="<?php echo e($item->name); ?>">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <h6 class="card-title text-truncate mb-2"><?php echo e($item->name); ?></h6>
                        <p class="text-muted small mb-3"><?php echo e(Str::limit($item->description, 80)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold text-danger"><?php echo e(number_format($item->price, 0, ',', ' ')); ?> ₽</span>
                            <a href="<?php echo e(route('products.show', $item)); ?>" class="btn btn-outline-primary btn-sm">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/products/show.blade.php ENDPATH**/ ?>