

<?php $__env->startSection('title', 'Обзор — панель UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-page-heading"><div><span class="eyebrow">UP_ZOO / ПАНЕЛЬ УПРАВЛЕНИЯ</span><h1>Обзор магазина</h1><p>Добро пожаловать, <?php echo e(auth()->user()->name); ?>. Здесь собраны основные показатели магазина.</p></div></div>
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Товары в каталоге</span><strong><?php echo e($productCount); ?></strong><small>позиций</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Всего заказов</span><strong><?php echo e($orderCount); ?></strong><small>заказов</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Новые заказы</span><strong><?php echo e($newOrderCount); ?></strong><small>требуют внимания</small></div></div>
        <div class="col-6 col-xl-3"><div class="admin-stat"><span>Покупатели</span><strong><?php echo e($userCount); ?></strong><small>аккаунтов</small></div></div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8"><section class="admin-panel"><div class="admin-panel-heading"><h2>Последние заказы</h2><a href="<?php echo e(route('admin.orders.index')); ?>">Все заказы →</a></div><div class="table-responsive"><table class="table admin-table"><thead><tr><th>Заказ</th><th>Покупатель</th><th>Дата</th><th>Сумма</th><th>Статус</th></tr></thead><tbody><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr><td><a href="<?php echo e(route('admin.orders.show', $order)); ?>">#<?php echo e($order->id); ?></a></td><td><?php echo e($order->customer_name); ?></td><td><?php echo e($order->created_at->format('d.m.Y')); ?></td><td><?php echo e(number_format((float) $order->total, 0, ',', ' ')); ?> ₽</td><td><span class="admin-status"><?php echo e($order->status); ?></span></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="text-center text-muted py-4">Заказов пока нет</td></tr><?php endif; ?></tbody></table></div></section></div>
        <div class="col-lg-4"><section class="admin-panel admin-review-alert"><span class="eyebrow">ОТЗЫВЫ</span><strong><?php echo e($pendingReviewCount); ?></strong><p>отзывов ожидают модерации</p><a class="btn btn-primary-soft" href="<?php echo e(route('admin.reviews.index')); ?>">Перейти к отзывам →</a></section></div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>