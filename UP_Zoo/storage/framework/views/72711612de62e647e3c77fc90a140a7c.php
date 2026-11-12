

<?php $__env->startSection('title', 'Корзина — UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <section class="page-hero"><div class="container"><span class="eyebrow">УЖЕ ПОЧТИ ДОМА</span><h1>Корзина</h1><p>Проверьте покупки, а мы бережно всё соберём.</p></div></section>
    <section class="section-block"><div class="container">
        <?php if($items->isEmpty()): ?>
            <div class="empty-state"><span>🧺</span><h2>Корзина пока пустая</h2><p>Здесь будут товары, которые вы выбрали для своего питомца.</p><a class="btn btn-primary-soft" href="<?php echo e(route('catalog')); ?>">Перейти в каталог <span>→</span></a></div>
        <?php else: ?>
            <div class="row g-4"><div class="col-lg-8"><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="cart-item"><div class="cart-product-icon"><?php echo e($item['product']->category?->icon ?? '🐾'); ?></div><div class="cart-product-info"><a href="<?php echo e(route('products.show', $item['product'])); ?>"><?php echo e($item['product']->name); ?></a><span><?php echo e(number_format((float) $item['product']->current_price, 0, ',', ' ')); ?> ₽ за шт.</span></div><form action="<?php echo e(route('cart.update', $item['product'])); ?>" method="POST" class="d-flex align-items-center gap-2"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?><label class="visually-hidden" for="quantity-<?php echo e($item['product']->id); ?>">Количество</label><input class="form-control quantity-input" id="quantity-<?php echo e($item['product']->id); ?>" type="number" name="quantity" min="1" max="<?php echo e(min($item['product']->stock, 99)); ?>" value="<?php echo e($item['quantity']); ?>"><button class="btn btn-outline-purple btn-sm" type="submit">Обновить</button></form><strong class="cart-line-total"><?php echo e(number_format((float) $item['product']->current_price * $item['quantity'], 0, ',', ' ')); ?> ₽</strong><form action="<?php echo e(route('cart.destroy', $item['product'])); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="remove-link" type="submit" aria-label="Удалить товар">×</button></form></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                <div class="col-lg-4"><div class="order-summary"><h2>Ваш заказ</h2><div><span>Товары</span><strong><?php echo e(number_format($total, 0, ',', ' ')); ?> ₽</strong></div><div><span>Доставка</span><strong>Рассчитаем при подтверждении</strong></div><hr><div class="summary-total"><span>Итого</span><strong><?php echo e(number_format($total, 0, ',', ' ')); ?> ₽</strong></div><a href="<?php echo e(route('checkout.create')); ?>" class="btn btn-primary-soft w-100 mt-3">Оформить заказ <span>→</span></a><a href="<?php echo e(route('catalog')); ?>" class="text-link d-block text-center mt-3">Продолжить покупки</a></div></div>
            </div>
        <?php endif; ?>
    </div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/pages/cart.blade.php ENDPATH**/ ?>