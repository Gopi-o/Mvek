<?php $__env->startSection('title', 'Каталог VR-очков'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4 bg-light">
    <div class="container">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Поиск</label>
                <input type="text" name="search" class="form-control" 
                       value="<?php echo e(request('search')); ?>" placeholder="Oculus, HTC Vive...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Категория</label>
                <select name="category" class="form-select">
                    <option value="">Все категории</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->slug); ?>" <?php echo e(request('category') == $cat->slug ? 'selected' : ''); ?>>
                            <?php echo e($cat->name); ?> (<?php echo e($cat->products_count); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Цена</label>
                <div class="row">
                    <div class="col-6">
                        <input type="number" name="min_price" class="form-control" 
                               value="<?php echo e(request('min_price')); ?>" placeholder="от">
                    </div>
                    <div class="col-6">
                        <input type="number" name="max_price" class="form-control" 
                               value="<?php echo e(request('max_price')); ?>" placeholder="до">
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <label class="form-label">Сортировка</label>
                <select name="sort" class="form-select">
                    <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>Цена: по возрастанию</option>
                    <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>Цена: по убыванию</option>
                    <option value="new" <?php echo e(request('sort') == 'new' ? 'selected' : ''); ?>>Новинки</option>
                </select>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary me-2">Применить</button>
                <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-outline-secondary">Сбросить</a>
            </div>
        </form>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Каталог товаров</h1>
            <span class="text-muted">Найдено: <?php echo e($products->total()); ?> товаров</span>
        </div>

        <?php if($products->count() > 0): ?>
            <div class="row g-4">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm hover-shadow border-0">
                        <div class="card-img-top position-relative" style="height: 220px;">
                            <img src="<?php echo e($product->image ? asset('storage/img/' . $product->image) : asset('storage/img/vr-ochki-def.jpg')); ?>" 
                                 class="w-100 h-100 object-fit-cover" alt="<?php echo e($product->name); ?>">
                            <?php if($product->stock == 0): ?>
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Нет в наличии</span>
                            <?php else: ?>
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    <?php echo e($product->stock); ?> шт.
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge bg-primary mb-2"><?php echo e($product->category->name); ?></span>
                            <h6 class="card-title mb-2">
                                <a href="<?php echo e(route('products.show', $product)); ?>" class="text-decoration-none text-dark"><?php echo e($product->name); ?></a>
                            </h6>
                            <p class="card-text text-muted small flex-grow-1"><?php echo e(Str::limit($product->description, 80)); ?></p>
                            
                            <div class="mt-2 d-flex gap-2 justify-content-between align-items-center">
                                <div class="h4 text-danger fw-bold mb-0 flex-grow-1">
                                    <?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽
                                </div>
                                
                                <?php if($product->stock > 0): ?>
                                    <div class="d-flex gap-1">
                                        <button onclick="addToCart(<?php echo e($product->id); ?>, '<?php echo e($product->name); ?>', '<?php echo e($product->image); ?>', <?php echo e($product->price); ?>)" class="btn btn-success btn-sm">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                        
                                        <form method="POST" action="<?php echo e(route('compare.add')); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm px-2" title="Добавить в сравнение">
                                                <i class="fas fa-balance-scale"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="badge bg-danger fs-6 px-3 py-2">Нет в наличии</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <nav class="mt-5 catalog-pagination">
                <?php echo e($products->appends(request()->query())->links()); ?>

            </nav>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-4"></i>
                <h4>Товары не найдены</h4>
                <p class="text-muted">Попробуйте изменить фильтры или поисковый запрос</p>
                <a href="<?php echo e(route('catalog.index')); ?>" class="btn btn-primary">Показать все товары</a>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.hover-shadow { transition: all 0.3s ease; }
.hover-shadow:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important; }
.object-fit-cover { object-fit: cover; }
.catalog-pagination a,
.catalog-pagination span {
    padding: 0.25rem 0.6rem !important;
    font-size: 0.875rem !important;
}
.catalog-pagination svg {
    width: 14px;
    height: 14px;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Suorce\VS_CODE\WEB\Mvek\resources\views/catalog/index.blade.php ENDPATH**/ ?>