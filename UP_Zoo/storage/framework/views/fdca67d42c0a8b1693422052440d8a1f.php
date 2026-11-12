<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#7c1ca6">
    <title><?php echo $__env->yieldContent('title', 'UP_ZOO — забота в каждом хвостике'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/upzoo.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="topline">Бережная доставка для хвостиков — от 2 000 ₽ бесплатно</div>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
        <div class="container">
            <a class="navbar-brand brand-mark" href="<?php echo e(route('home')); ?>"><span class="brand-paw">✿</span> UP_<span>ZOO</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Открыть меню">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavigation">
                <div class="navbar-nav mx-auto">
                    <a class="nav-link" href="<?php echo e(route('about')); ?>">О магазине</a>
                    <a class="nav-link" href="<?php echo e(route('catalog')); ?>">Каталог</a>
                    <a class="nav-link" href="<?php echo e(route('promotions')); ?>">Акции</a>
                    <a class="nav-link" href="<?php echo e(route('new')); ?>">Новинки</a>
                    <a class="nav-link" href="<?php echo e(route('brands')); ?>">Бренды</a>
                    <a class="nav-link" href="<?php echo e(route('reviews.index')); ?>">Отзывы</a>
                    <a class="nav-link" href="<?php echo e(route('contacts')); ?>">Контакты</a>
                </div>
                <div class="nav-actions">
                    <a class="nav-icon-link" href="<?php echo e(route('cart.index')); ?>" aria-label="Корзина">Корзина <span class="cart-count"><?php echo e(array_sum(session('cart', []))); ?></span></a>
                    <?php if(auth()->guard()->check()): ?>
                        <a class="nav-icon-link" href="<?php echo e(route('account')); ?>">Кабинет</a>
                        <?php if(auth()->user()->hasAnyRole(['admin', 'manager'])): ?>
                            <a class="nav-icon-link" href="<?php echo e(route('admin.dashboard')); ?>">Панель</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="nav-icon-link" href="<?php echo e(route('login')); ?>">Войти</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <?php if(session('success')): ?>
            <div class="container pt-3"><div class="alert alert-success alert-dismissible fade show mb-0" role="status"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button></div></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="container pt-3"><div class="alert alert-danger alert-dismissible fade show mb-0" role="alert"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button></div></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row g-4 py-5">
                <div class="col-lg-4">
                    <a class="navbar-brand brand-mark text-white" href="<?php echo e(route('home')); ?>"><span class="brand-paw">✿</span> UP_<span>ZOO</span></a>
                    <p class="footer-copy mt-3">Маленькие радости для тех, кто делает наш дом теплее.</p>
                </div>
                <div class="col-6 col-lg-2"><h2 class="footer-heading">Магазин</h2><a href="<?php echo e(route('about')); ?>">О нас</a><a href="<?php echo e(route('catalog')); ?>">Каталог</a><a href="<?php echo e(route('promotions')); ?>">Акции</a><a href="<?php echo e(route('brands')); ?>">Бренды</a></div>
                <div class="col-6 col-lg-2"><h2 class="footer-heading">Покупателям</h2><a href="<?php echo e(route('reviews.index')); ?>">Отзывы</a><a href="<?php echo e(route('contacts')); ?>">Контакты</a><a href="<?php echo e(route('cart.index')); ?>">Корзина</a><?php if(auth()->guard()->check()): ?><a href="<?php echo e(route('account')); ?>">Личный кабинет</a><?php else: ?><a href="<?php echo e(route('login')); ?>">Войти</a><?php endif; ?></div>
                <div class="col-lg-4"><h2 class="footer-heading">Всегда на связи</h2><a href="tel:+78005553535">8 800 555-35-35</a><a href="mailto:hello@upzoo.ru">hello@upzoo.ru</a><p class="footer-copy mt-2">Ежедневно, 9:00–21:00</p></div>
            </div>
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2"><span>© <?php echo e(date('Y')); ?> UP_ZOO. Забота в каждом хвостике.</span><span>Цены указаны в рублях. Информация на сайте носит ознакомительный характер.</span></div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\UP_Zoo\resources\views/layouts/app.blade.php ENDPATH**/ ?>