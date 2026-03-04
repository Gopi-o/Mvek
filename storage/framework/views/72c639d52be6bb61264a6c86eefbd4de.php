<?php $__env->startSection('title', 'Мои заказы'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list me-2"></i>Мои заказы</h2>
        <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart me-1"></i>Корзина
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($orders->count() > 0): ?>
        <div class="row g-4">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-<?php echo e($order->status == 'new' ? 'warning' : 'success'); ?>">
                                    <?php echo e($order->status == 'new' ? 'Новый' : 'Выполнен'); ?>

                                </span>
                                <span class="fw-bold text-danger fs-6"><?php echo e($order->total); ?> ₽</span>
                            </div>
                            <h6 class="card-title mb-3">#<?php echo e($order->id); ?></h6>
                            <p class="small text-muted mb-2"><i class="fas fa-user me-1"></i><?php echo e($order->name); ?></p>
                            <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt me-1"></i><?php echo e($order->city); ?></p>
                            <p class="small text-muted mb-3"><i class="fas fa-phone me-1"></i><?php echo e($order->phone); ?></p>
                            <small class="text-muted"><?php echo e($order->created_at->format('d.m.Y H:i')); ?></small>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0">
                            <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-outline-primary btn-sm w-100">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="d-flex justify-content-center mt-5">
            <?php echo e($orders->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-4 opacity-50"></i>
            <h5 class="text-muted mb-3">У вас пока нет заказов</h5>
            <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-store me-2"></i>Перейти в каталог
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/orders/index.blade.php ENDPATH**/ ?>