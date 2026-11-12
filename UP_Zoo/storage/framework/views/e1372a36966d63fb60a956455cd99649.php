<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Панель управления — UP_ZOO'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/upzoo.css')); ?>" rel="stylesheet">
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <div class="container-fluid d-flex align-items-center justify-content-between gap-3">
            <a class="brand-mark" href="<?php echo e(route('admin.dashboard')); ?>"><span class="brand-paw">✿</span> UP_<span>ZOO</span> <small>ПАНЕЛЬ УПРАВЛЕНИЯ</small></a>
            <div class="d-flex align-items-center gap-3"><span class="admin-user"><?php echo e(auth()->user()->name); ?> · <?php echo e(auth()->user()->role === 'admin' ? 'Администратор' : 'Менеджер'); ?></span><a class="admin-back-link" href="<?php echo e(route('home')); ?>">На сайт ↗</a></div>
        </div>
    </header>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <span class="admin-nav-label">УПРАВЛЕНИЕ</span>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.dashboard')]); ?>">Обзор</a>
            <?php if(auth()->user()->role === 'admin'): ?>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.products.*')]); ?>">Товары</a>
                <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.categories.*')]); ?>">Категории</a>
                <a href="<?php echo e(route('admin.brands.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.brands.*')]); ?>">Бренды</a>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.orders.*')]); ?>">Заказы</a>
            <a href="<?php echo e(route('admin.reviews.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.reviews.*')]); ?>">Отзывы</a>
            <?php if(auth()->user()->role === 'admin'): ?>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => request()->routeIs('admin.users.*')]); ?>">Пользователи</a>
            <?php endif; ?>
            <div class="admin-sidebar-bottom"><a href="<?php echo e(route('account')); ?>">Личный кабинет</a><form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?><button type="submit">Выйти</button></form></div>
        </aside>
        <main class="admin-content">
            <?php if(session('success')): ?><div class="alert alert-success"><?php echo e(session('success')); ?></div><?php endif; ?>
            <?php if($errors->any()): ?><div class="alert alert-danger"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div><?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/layouts/admin.blade.php ENDPATH**/ ?>