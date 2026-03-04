<?php $__env->startSection('title', 'Оформление заказа'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 pb-0">
                    <h3 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-credit-card me-2 text-success"></i>
                        Оформление заказа
                    </h3>
                </div>
                
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Товар</th>
                                        <th class="border-0 text-center">Кол-во</th>
                                        <th class="border-0 text-end">Цена</th>
                                        <th class="border-0 text-end">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $product = $products->firstWhere('id', $id);
                                    ?>
                                    <?php if($product): ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-light rounded-circle p-2" style="width: 50px; height: 50px;">
                                                    <?php if($product->image): ?>
                                                        <img src="<?php echo e(asset('storage/img/' . $product->image)); ?>" 
                                                             class="w-100 h-100 rounded-circle object-fit-cover" alt="<?php echo e($product->name); ?>">
                                                    <?php else: ?>
                                                        <i class="fas fa-vr-cardboard text-primary fs-5"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?php echo e($product->name); ?></h6>
                                                    <small class="text-muted"><?php echo e($product->category->name ?? ''); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge bg-success fs-6"><?php echo e($item['quantity']); ?></span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽
                                        </td>
                                        <td class="align-middle text-end fw-bold text-success">
                                            <?php echo e(number_format($product->price * $item['quantity'], 0, ',', ' ')); ?> ₽
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="p-4 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h5 fw-bold text-dark">Итого:</span>
                            <span class="h3 text-danger fw-bold mb-0"><?php echo e(number_format($total, 0, ',', ' ')); ?> ₽</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <form method="POST" action="<?php echo e(route('orders.store')); ?>">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Контактные данные</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Имя *</label>
                                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Телефон *</label>
                                            <input type="tel" name="phone" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-3">Доставка</h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Город *</label>
                                            <input type="text" name="city" class="form-control <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-semibold">Адрес *</label>
                                            <input type="text" name="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   placeholder="ул. Ленина, д. 5, кв. 12" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="self-delivery" name="delivery" value="self">
                                                <label class="form-check-label" for="self-delivery">
                                                    Самовывоз (бесплатно)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-success btn-lg py-3 fs-5 fw-bold shadow-lg">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Оформить заказ на <?php echo e(number_format($total, 0, ',', ' ')); ?> ₽
                                </button>
                                <a href="<?php echo e(route('cart.index')); ?>" class="btn btn-outline-secondary py-2">
                                    <i class="fas fa-arrow-left me-2"></i>Назад к корзине
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.object-fit-cover { object-fit: cover; }
.table-hover tbody tr:hover { background-color: rgba(0,0,0,0.02); }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/cart/checkout.blade.php ENDPATH**/ ?>