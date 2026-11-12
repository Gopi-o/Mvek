<article class="product-card h-100">
    <a class="product-visual" href="<?php echo e(route('products.show', $product)); ?>" aria-label="<?php echo e($product->name); ?>">
        <?php if($product->sale_price): ?><span class="product-badge">Выгодно</span><?php elseif($product->is_new): ?><span class="product-badge badge-new">Новинка</span><?php endif; ?>
        <?php if($product->image_url): ?>
            <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" loading="lazy">
        <?php else: ?>
            <span class="product-emoji"><?php echo e($product->category?->icon ?? '🐾'); ?></span>
            <span class="product-visual-label"><?php echo e($product->category?->name ?? 'Для питомцев'); ?></span>
        <?php endif; ?>
    </a>
    <div class="product-info">
        <div class="product-meta"><?php echo e($product->brand?->name ?? 'UP_ZOO'); ?><?php if($product->category): ?> · <?php echo e($product->category->name); ?><?php endif; ?></div>
        <a class="product-title" href="<?php echo e(route('products.show', $product)); ?>"><?php echo e($product->name); ?></a>
        <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
            <div class="product-price"><?php echo e(number_format((float) $product->current_price, 0, ',', ' ')); ?> ₽ <?php if($product->sale_price): ?><del><?php echo e(number_format((float) $product->price, 0, ',', ' ')); ?> ₽</del><?php endif; ?></div>
            <?php if($product->stock > 0): ?>
                <form action="<?php echo e(route('cart.store', $product)); ?>" method="POST"><?php echo csrf_field(); ?><button class="btn btn-add" type="submit" aria-label="Добавить в корзину">+</button></form>
            <?php else: ?>
                <span class="text-muted small">Нет в наличии</span>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/partials/product-card.blade.php ENDPATH**/ ?>