

<?php $__env->startSection('title', 'Войти — UP_ZOO'); ?>

<?php $__env->startSection('content'); ?>
    <section class="auth-section"><div class="auth-card"><span class="auth-icon">🐾</span><span class="eyebrow">С ВОЗВРАЩЕНИЕМ</span><h1>Войти в кабинет</h1><p>Ваш питомец уже ждёт маленький сюрприз.</p><form action="<?php echo e(route('login.store')); ?>" method="POST" class="row g-3 text-start"><?php echo csrf_field(); ?><div class="col-12"><label class="form-label" for="email">Электронная почта</label><input class="form-control" id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" required></div><div class="col-12"><label class="form-label" for="password">Пароль</label><input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required></div><div class="col-12 form-check ms-2"><input class="form-check-input" id="remember" name="remember" type="checkbox" value="1"><label class="form-check-label" for="remember">Запомнить меня</label></div><div class="col-12"><button class="btn btn-primary-soft w-100" type="submit">Войти <span>→</span></button></div></form><div class="auth-bottom">Впервые у нас? <a href="<?php echo e(route('register')); ?>">Создать аккаунт</a></div></div></section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/auth/login.blade.php ENDPATH**/ ?>