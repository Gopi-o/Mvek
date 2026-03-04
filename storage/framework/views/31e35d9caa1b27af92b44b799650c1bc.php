<?php $__env->startSection('title', 'Корзина'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1>Корзина покупок</h1>
    
    <?php if(!empty($cart)): ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Кол-во</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $product = $products->firstWhere('id', $id) ?>
                        <?php if($product): ?>
                        <tr>
                            <td><?php echo e($product->name); ?></td>
                            <td><?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽</td>
                            <td><?php echo e($item['quantity']); ?></td>
                            <td class="fw-bold"><?php echo e(number_format($product->price * $item['quantity'], 0, ',', ' ')); ?> ₽</td>
                            <td>
                                <button onclick="removeFromCart('<?php echo e($id); ?>')" 
                                        class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-end">
            <h3>Итого: <?php echo e(number_format($total, 0, ',', ' ')); ?> ₽</h3>
            <a href="<?php echo e(route('cart.checkout')); ?>" class="btn btn-success btn-lg">Оформить заказ</a>
            <button type="button" class="btn btn-outline-danger" onclick="clearCartPage()">Очистить</button>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h4>Корзина пуста</h4>
            <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-primary">В каталог</a>
        </div>
    <?php endif; ?>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function clearCartPage() {
    if (!confirm('Очистить корзину?')) return;
    fetch('<?php echo e(route("cart.clear")); ?>', {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
    })
    .then(r => r.json())
    .then(() => location.reload())
    .catch(() => alert('Ошибка'));
}

function removeFromCart(productId) {
    if (!confirm('Удалить товар из корзины?')) return;
    
    fetch('<?php echo e(route("cart.remove")); ?>', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ product_id: productId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
            showToast(data.message);
        }
    })
    .catch(e => showToast('Ошибка удаления'));
}

function showToast(msg) {
    const toast = document.createElement('div');
    toast.innerHTML = `<div class="alert alert-success">${msg}</div>`;
    toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;width:300px;';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/cart/index.blade.php ENDPATH**/ ?>