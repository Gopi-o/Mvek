<?php $__env->startSection('title', 'Заказ #' . $order->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient text-white border-0 py-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-1 fw-bold">
                                <i class="fas fa-check-circle me-2"></i>
                                Заказ #<?php echo e($order->id); ?>

                            </h2>
                            <div class="badge bg-<?php echo e($order->status == 'new' ? 'warning' : 'success'); ?> fs-6 px-3 py-2">
                                <?php echo e($order->status == 'new' ? 'Новый' : 'Подтвержден'); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="h4 fw-bold text-white mb-0">
                                <?php echo e(number_format($order->total, 0, ',', ' ')); ?> ₽
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-list me-2"></i>Товары
                        </h5>
                        <div class="row g-3">
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12">
                                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                    <div class="bg-white rounded-circle p-2 shadow-sm" style="width: 60px; height: 60px;">
                                        <?php if($item->product->image): ?>
                                            <img src="<?php echo e(asset('storage/img/' . $item->product->image)); ?>" 
                                                 class="w-100 h-100 rounded-circle object-fit-cover" alt="<?php echo e($item->product->name); ?>">
                                        <?php else: ?>
                                            <i class="fas fa-vr-cardboard text-primary fs-4"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold"><?php echo e($item->product->name); ?></h6>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="badge bg-success"><?php echo e($item->quantity); ?> шт.</span>
                                            <span><?php echo e($item->product->category->name ?? ''); ?></span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="h5 fw-bold text-success mb-0">
                                            <?php echo e(number_format($item->price * $item->quantity, 0, ',', ' ')); ?> ₽
                                        </div>
                                        <small class="text-muted"><?php echo e(number_format($item->price, 0, ',', ' ')); ?> ₽ / шт.</small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="row g-0">
                        <div class="col-md-6 p-4 bg-light border-end">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-user me-2"></i>Покупатель
                            </h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <strong>Имя:</strong><br>
                                    <?php echo e($order->name); ?>

                                </div>
                                <div class="col-6">
                                    <strong>Телефон:</strong><br>
                                    <?php echo e($order->phone); ?>

                                </div>
                                <?php if($order->email): ?>
                                <div class="col-12">
                                    <strong>Email:</strong><br>
                                    <?php echo e($order->email); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="col-md-6 p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="fas fa-map-marker-alt me-2"></i>Доставка
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <strong><?php echo e($order->delivery == 'self' ? 'Самовывоз' : 'Доставка'); ?>:</strong><br>
                                    <?php echo e($order->city); ?><br>
                                    <?php echo e($order->address); ?>

                                </div>
                                <div class="col-12">
                                    <strong>Дата:</strong><br>
                                    <span class="text-muted"><?php echo e($order->created_at->format('d.m.Y H:i')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Общая сумма:</span>
                            <span class="h3 fw-bold mb-0"><?php echo e(number_format($order->total, 0, ',', ' ')); ?> ₽</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pt-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-primary py-2">
                            <i class="fas fa-shopping-bag me-2"></i>Продолжить покупки
                        </a>
                        <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-shopping-cart me-2"></i>В корзину
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}
.object-fit-cover { object-fit: cover; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/orders/show.blade.php ENDPATH**/ ?>